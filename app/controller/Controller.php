<?php

namespace app\controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Route\NamespaceController;
use LinFly\Annotation\Route\PostRoute;
use LinFly\Annotation\Route\Route;
use support\Db;
use support\Request;
use support\Response;

#[NamespaceController(namespace: __NAMESPACE__)]
class Controller
{

    #[Inject]
    private IndexService $indexService;

    #[Route("")]
    public function index(Request $request,#[Inject("TEST_ABCD")] $abc = 0): Response
    {
        return $this->indexService->index($abc);
    }

    #[PostRoute]
    public function php(Request $request, string $code)
    {
        if($code == null) {
            return "error";
        }
        return $this->indexService->php($code);
    }
}