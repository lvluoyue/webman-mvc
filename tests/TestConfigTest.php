<?php

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class TestConfigTest extends TestCase
{
    #[Test]
    #[TestDox("fgf")]
    public function testAppConfig()
    {
        $config = config('app');
        self::assertIsArray($config);
        self::assertArrayHasKey('debug', $config);
        self::assertIsBool($config['debug']);
        self::assertArrayHasKey('default_timezone', $config);
        self::assertIsString($config['default_timezone']);
    }
}