<?php

namespace tests\controller;

use app\service\IndexService;
use app\service\impl\IndexServiceImpl;
use DI\Attribute\Inject;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use support\Container;

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
