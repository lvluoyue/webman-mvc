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
    private static IndexService $controller;

    public static function setUpBeforeClass(): void
    {
        static::$controller = container::get(IndexService::class);
    }

    #[Test]
    public function testIndex()
    {
//        print_r(\DI\env("test_abc"));
        self::assertIsString(static::$controller->index());
    }
}
