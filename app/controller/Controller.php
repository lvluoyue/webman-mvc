<?php

namespace app\controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Route\NamespaceController;
use LinFly\Annotation\Route\Route;
use support\Request;

#[NamespaceController(namespace: __NAMESPACE__)]
class Controller
{

    #[Inject]
    private IndexService $indexService;


    #[Route("")]
    public function index(Request $request): string
    {
        return $this->indexService->index("index");
    }

}