<?php

namespace tests\controller;

use app\web\service\IndexService;
use DI\Attribute\Inject;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use support\Container;

class IndexControllerTest extends TestCase
{

    #[Inject]
    private IndexService $controller;

    public function setUp(): void
    {
        Container::injectOn($this);
    }

    #[Test]
    public function index()
    {
        $data = $this->controller->json();
        self::assertIsArray($data);
        self::assertArrayHasKey('data', $data);
        self::assertIsString($data['data'], 'data不是字符串');
    }
}
