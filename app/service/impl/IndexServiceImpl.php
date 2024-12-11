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

    public function mysql(): Response
    {
//        $waitGroup = new WaitGroup();
//        $waitGroup->add();
//        $coroutine1 = new Coroutine(function () use ($waitGroup) {
//            // do something
//            sleep(3);
//            $waitGroup->done();
//            return 1;
//        });
//        $waitGroup->add();
//        $coroutine2 = new Coroutine(function () use ($waitGroup) {
//            // do something
//            sleep(2);
//            $waitGroup->done();
//            return 2;
//        });
//        $timeOne = microtime(true);
//        $waitGroup->wait();
//        echo '[x] [协程1] ' . $coroutine1->id() . PHP_EOL;
//        echo '[x] [协程2] ' . $coroutine2->id() . PHP_EOL;
//        $timeTwo = microtime(true);
//        echo '[x] [运行时间] ' . ($timeTwo - $timeOne) . PHP_EOL;
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
        $cmd = "docker run --rm -i -u nobody php php -r \"$str\"";
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
        $cmd = "docker run --rm -i -u nobody -v $codeDir:/opt openjdk bash -c \"cd /opt && java Main.java\"";
        docker_it($cmd, $input, $result['output'], $result['error'], $result['runningTime']);
        return json($result);
    }
}