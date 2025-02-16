<?php

namespace app\web\controller;

use app\web\service\IndexService;
use Composer\InstalledVersions;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\GetMapping;
use LinFly\Annotation\Attributes\Route\RequestMapping;
use Luoyue\WebmanMvcCore\annotation\authentication\Anonymous;
use Luoyue\WebmanMvcCore\annotation\authorization\hasRole;
use support\Request;
use support\Response;
use WebmanTech\Auth\Auth;
use Workerman\Worker;

class IndexController
{
    #[Inject]
    private readonly IndexService $indexService;

    #[GetMapping]
    #[Anonymous]
    public function json(): Response
    {
        return json($this->indexService->json());
    }

    #[RequestMapping('')]
    #[hasRole('admin')]
    public function index(Request $request): Response
    {
        $eventLoop = Worker::getEventLoop()::class;
        $workermanVersion = InstalledVersions::getVersion('workerman/workerman');
        $webmanVersion = InstalledVersions::getVersion('workerman/webman-framework');
        $userId = Auth::guard()->getId();

        return \response(<<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <body>
                <h1>Hello World</h1>
                <p>Workerman Version: {$workermanVersion}</p>
                <p>Webman Version: {$webmanVersion}</p>
                <p>event loop: {$eventLoop}</p>
                <p>user id:{$userId}</p>
                <button type="submit" onclick="location.href='/api/logout'">logout</button>
            </body>
            </html>
            HTML);
    }
}
