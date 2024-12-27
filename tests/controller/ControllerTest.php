<?php

namespace controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use support\Container;

class ControllerTest extends TestCase
{

    #[Inject]
    private IndexService $controller;

    public function setUp(): void
    {
        Container::injectOn($this);
    }

    #[Test]
    public function testIndex()
    {
        self::assertIsObject($this->controller->index());
    }
}
