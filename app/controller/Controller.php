<?php

namespace app\controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\Middleware;
use LinFly\Annotation\Attributes\Route\NamespaceController;
use LinFly\Annotation\Attributes\Route\PostMapping;
use LinFly\Annotation\Attributes\Route\RequestMapping;
use support\Request;
use support\Response;

#[Middleware(\app\middleware\testMiddleware::class)]
#[NamespaceController(namespace: __NAMESPACE__)]
class Controller
{
    #[Inject]
    private readonly IndexService $indexService;

    #[RequestMapping("")]
    public function index(Request $request,#[Inject("TEST_ABCD")] $abc): Response
    {
        return $this->indexService->index($abc);
    }

    #[PostMapping]
    public function php(Request $request, string $code): Response
    {
        return $this->indexService->php($code);
    }

    #[PostMapping]
    public function java(Request $request, string $code, string $input): Response
    {
        return $this->indexService->java($code, $input);
    }
}