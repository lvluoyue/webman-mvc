<?php

namespace app\controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Route\NamespaceController;
use LinFly\Annotation\Route\PostRoute;
use LinFly\Annotation\Route\Route;
use support\Request;
use support\Response;

#[NamespaceController(namespace: __NAMESPACE__)]
class Controller
{

    #[Inject]
    private IndexService $indexService;

    #[Route("")]
    public function index(Request $request): Response
    {
        return $this->indexService->index("index");
    }

    #[PostRoute]
    public function php(Request $request, string $code)
    {
        if($code == null) {
            return "error";
        }
        return $this->indexService->php();
    }
}