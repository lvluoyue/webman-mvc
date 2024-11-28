<?php

namespace app\controller;

use app\annotation\Value;
use app\po\testPO;
use app\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Route\GetRoute;
use LinFly\Annotation\Route\Middleware;
use LinFly\Annotation\Route\NamespaceController;
use LinFly\Annotation\Route\Controller;
use LinFly\Annotation\Route\PostRoute;
use LinFly\Annotation\Route\Route;
use support\Request;

//#[Controller("/test")]
#[NamespaceController(namespace: __NAMESPACE__)]
class TestController
{

    #[Inject]
    private IndexService $indexService;

    #[Route("")]
    public function index(Request $request): string
    {
        return $this->indexService->index("test");
    }

    #[Route]
    public function mysql(Request $request): string
    {
        return $this->indexService->mysql();
    }

    #[GetRoute("{id:\d+}")]
    public function hello(int $id): string
    {
        return 'hello' . $id;
    }

    #[PostRoute("test/{id:\d+}")]
    public function post(int $id, testPO $testPO)
    {
        return json(["id" => $id,
            ...$testPO->get()
        ]);
    }

    #[Route]
    public function view(Request $request)
    {
        return view('index/view', ['name' => 'webman']);
    }

    #[Route]
    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
