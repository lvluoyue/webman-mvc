<?php

namespace controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use support\Container;

class ControllerTest extends TestCase
{

    #[Inject]
    private IndexService $controller;

    public function __construct(string $name)
    {
        Container::injectOn($this);
        parent::__construct($name);
    }

    #[Test]
    public function testIndex()
    {
        self::assertIsObject($this->controller->index());
    }

    #[Test]
    public function testIndex1()
    {
        self::assertIsObject($this->controller->index());
    }
}
