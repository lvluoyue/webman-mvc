<?php

namespace app\web\controller;

use app\web\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\PostMapping;
use LinFly\Annotation\Attributes\Route\RequestMapping;
use Luoyue\WebmanMvcCore\annotation\authentication\Anonymous;
use Luoyue\WebmanMvcCore\annotation\authorization\hasRole;
use Luoyue\WebmanMvcCore\handler\bean\SessionUserDetailsService;
use support\Request;
use support\Response;

class IndexController
{
    #[Inject]
    private readonly IndexService $indexService;

    #[Inject]
    private readonly SessionUserDetailsService $sessionUserDetailsService;

    #[RequestMapping('')]
    #[hasRole('index')]
    public function index(Request $request): Response
    {
        return json($this->indexService->index());
    }

    #[PostMapping]
    #[Anonymous]
    public function login(string $username, string $password)
    {
        if (!$this->sessionUserDetailsService->login($username, $password)) {
            return json(['code' => 201, 'msg' => '登录失败']);
        }
        return redirect('/');
    }

}
