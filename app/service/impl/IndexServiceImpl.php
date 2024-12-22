<?php

namespace app\service\impl;

use app\annotation\Component;
use app\service\IndexService;
use DI\Attribute\Inject;
use support\Db;
use support\Response;
use Webman\Route;
use Workbunny\WebmanCoroutine\Utils\Coroutine\Coroutine;
use Workbunny\WebmanCoroutine\Utils\WaitGroup\WaitGroup;
use Workerman\Protocols\Http\Chunk;
use Workerman\Protocols\Http\ServerSentEvents;
use Workerman\Timer;
use function \Workbunny\WebmanCoroutine\sleep;
use function Workbunny\WebmanCoroutine\is_coroutine_env;

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