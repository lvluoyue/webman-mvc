<?php

namespace app\service\impl;

use app\annotation\Component;
use app\service\IndexService;
use DI\Attribute\Inject;
use support\Db;
use support\Response;
use Webman\Route;
use Workbunny\WebmanCoroutine\Utils\Coroutine\Coroutine;
use Workbunny\WebmanCoroutine\Utils\WaitGroup\WaitGroup;
use Workerman\Protocols\Http\Chunk;
use Workerman\Protocols\Http\ServerSentEvents;
use Workerman\Timer;
use function \Workbunny\WebmanCoroutine\sleep;

#[Component]
class IndexServiceImpl implements IndexService
{
    #[Inject("TEST_ABC")]
    private string $abc;

    public function index(string $v): Response
    {
//        dump(Route::getRoutes());
        return json([
            'code' => 200,
            'message' => 'success',
            'data' => "IndexServiceImpl::abc=" . $this->abc . ',inject abcd=' . $v,
        ]);
    }

    public function sse(): Response
    {
        $connection = request()->connection;
        $waitGroup = new WaitGroup();
        $waitGroup->add(30);
        for ($i = 1; $i <= 30; $i++) {
            /** @var Coroutine[] $coroutine */
            $coroutine[$i] = new Coroutine(function () use ($waitGroup, $connection, $i, &$coroutine) {
                sleep(0.1 * $i);
                $connection->send(new ServerSentEvents([
                    'event' => 'message',
                    'data' => 'hello' . $i,
                    'id' => $i
                ]));
                $waitGroup->done();
            });
        }

        $timeOne = microtime(true);
        //设置定时器
        $timer_id = Timer::add(1, function () use ($connection, $waitGroup, &$timer_id, $timeOne) {
            // 发送完毕，断开客户端的tcp连接
            if ($waitGroup->count() == 0) {
                Timer::del($timer_id);
                $connection->close(new ServerSentEvents([
                    'event' => 'close',
                    'data' => 'close',
                    'id' => 0
                ]));
                $timeTwo = microtime(true);
                echo '[协程] [运行时间] ' . ($timeTwo - $timeOne) . PHP_EOL;
            }
        });

        //tcp关闭连接后立刻停止协程
        $connection->onClose = function () use($timer_id, &$coroutine) {
            Timer::del($timer_id);
            foreach ($coroutine as $unset) {
                $unset->getCoroutineInterface()->kill(new \Exception);
            }
        };

        return response("\r\n")->withHeaders([
            "Content-Type" => "text/event-stream",
        ]);
    }

    function chunked(): Response
    {
        $connection = request()->connection;
        $waitGroup = new WaitGroup();
        $waitGroup->add(30);
        for ($i = 1; $i <= 30; $i++) {
            $coroutine = new Coroutine(function () use ($waitGroup, $connection, $i) {
                sleep(0.1 * $i);
                $connection->send(new Chunk($i . " "));
                $waitGroup->done();
            });
        }

        $timeOne = microtime(true);
        //设置定时器
        $timer_id = Timer::add(1, function () use ($connection, $waitGroup, &$timer_id, $timeOne) {
            // 发送完毕，断开客户端的tcp连接
            if ($waitGroup->count() == 0) {
                Timer::del($timer_id);
                $connection->close(new Chunk(''));
                $timeTwo = microtime(true);
                echo '[协程] [运行时间] ' . ($timeTwo - $timeOne) . PHP_EOL;
            }
        });


        //tcp关闭连接后立刻停止协程
        $connection->onClose = function () use($timer_id, &$coroutine) {
            Timer::del($timer_id);
            foreach ($coroutine as $unset) {
                $unset->getCoroutineInterface()->kill(new \Exception);
            }
        };

        return response()->withHeaders([
            "Transfer-Encoding" => "chunked",
            "Content-Type" => "application/octet-stream" //二进制流
        ]);
    }

    public function mysql(): Response
    {
        return json(Db::table("api_call")->get());
    }

    function php(string $str): Response
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        $str = base64_decode($str);
        $str = str_replace("\"", "\\\"", $str);
        $cmd = "docker run --rm -iu nobody -w /opt:ro php php -r \"$str\"";
        docker_it($cmd, '', $result['output'], $result['error'], $result['runningTime']);
        return json($result);
    }

    function java(string $code, string $input): Response
    {
        $result = [
            'output' => '',
            'error' => '',
            'runningTime' => 0,
        ];
        $code = base64_decode($code);
        $codeDir = runtime_path('/cache/code/java/id/');
        if (!is_dir($codeDir)) {
            mkdir($codeDir, 0777, true);
        }
        file_put_contents($codeDir . 'Main.java', $code);
        $cmd = "docker run --rm -iu nobody -v $codeDir:/opt:ro -w /opt openjdk bash -c \"java Main.java\"";
        docker_it($cmd, $input, $result['output'], $result['error'], $result['runningTime']);
        unlink($codeDir . 'Main.java');
        return json($result);
    }

}