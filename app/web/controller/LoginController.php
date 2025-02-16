<?php

namespace app\web\controller;

use app\web\service\LoginService;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\Controller;
use LinFly\Annotation\Attributes\Route\GetMapping;
use LinFly\Annotation\Attributes\Route\PostMapping;
use Luoyue\WebmanMvcCore\annotation\authentication\Anonymous;
use support\Response;

#[Controller('/api')]
class LoginController
{
    #[Inject]
    private readonly LoginService $loginService;

    #[PostMapping]
    #[Anonymous]
    public function login(string $username, string $password): Response
    {
        if (!$this->loginService->login($username, $password)) {
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
        $this->loginService->logout();

        return redirect('/');
    }
}
