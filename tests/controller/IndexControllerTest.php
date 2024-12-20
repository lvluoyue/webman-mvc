<?php

namespace controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use support\Container;

#[TestDox("测试")]
class IndexControllerTest extends TestCase
{

    #[Inject]
    private IndexService $controller;

    public function __construct(string $name)
    {
        Container::injectOn($this);
        parent::__construct($name);
    }

    #[Test]
    #[TestDox("测试 IndexController::index 方法")]
    public function testIndex()
    {
        self::assertIsObject($this->controller->index('index'));
    }

    #[Test]
    public function testIndex1()
    {
        self::assertIsObject($this->controller->index('index'));
    }
}
