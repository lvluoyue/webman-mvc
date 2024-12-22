<?php

namespace app\service\impl;

use app\annotation\Component;
use app\service\IndexService;
use DI\Attribute\Inject;
use support\Response;

#[Component]
class IndexServiceImpl implements IndexService
{
    #[Inject("TEST_DATA")]
    private string $data;

    public function index(): Response
    {
        return json([
            'code' => 200,
            'message' => 'success',
            'data' => $this->data
        ]);
    }

}