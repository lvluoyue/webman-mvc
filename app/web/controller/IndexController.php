<?php

namespace app\web\controller;

use app\web\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\GetMapping;
use LinFly\Annotation\Attributes\Route\PostMapping;
use LinFly\Annotation\Attributes\Route\RequestMapping;
use Luoyue\WebmanMvcCore\annotation\authentication\Anonymous;
use Luoyue\WebmanMvcCore\annotation\authorization\hasRole;
use Luoyue\WebmanMvcCore\handler\bean\SessionUserDetailsService;
use support\Request;
use support\Response;
use Workerman\Worker;

class IndexController
{
    #[Inject]
    private readonly IndexService $indexService;

    #[Inject]
    private readonly SessionUserDetailsService $sessionUserDetailsService;

    #[GetMapping]
    #[Anonymous]
    public function json(): Response
    {
        return json($this->indexService->json());
    }

    #[RequestMapping('')]
    #[hasRole('index')]
    public function index(Request $request): Response
    {
        $eventLoop = Worker::getEventLoop()::class;

        return \response(<<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <body>
                <h3>event loop: {$eventLoop}</h3>
                <button type="submit" onclick="location.href='/logout'">logout</button>
            </body>
            </html>
            HTML);
    }

    #[PostMapping]
    #[Anonymous]
    public function login(string $username, string $password): Response
    {
        if (!$this->sessionUserDetailsService->login($username, $password)) {
            return \response(<<<'HTML'
                <!DOCTYPE html>
                <html lang="en">
                <body>
                <h3>登录失败</h3>
                    <button type="submit" onclick="location.href='/'">重新登录</button>
                </body>
                </html>
                HTML);
        }

        return redirect('/');
    }

    #[GetMapping]
    #[Anonymous]
    public function logout()
    {
        $this->sessionUserDetailsService->logout();

        return redirect('/');
    }
}
