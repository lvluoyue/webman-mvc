<?php

namespace app\controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\RequestMapping;
use Luoyue\WebmanMvcCore\annotation\authorization\hasRole;
use support\Request;
use support\Response;

class IndexController
{
    #[Inject]
    private readonly IndexService $indexService;

    #[RequestMapping('')]
    #[hasRole('writer')]
    public function index(Request $request): Response
    {
        return json($this->indexService->index());
    }
}
