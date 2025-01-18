<?php

namespace app\annotation\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use ReflectionClass;
use support\Container;

class ServiceParser implements IAnnotationParser
{
    public static function process(array $item): void
    {
        $reflectionClass = new ReflectionClass($item['class']);
        $class = Container::get($item['class']);
        if ($name = $item['parameters']['name']) {
            self::addContainer($name, $class);
        } else {
            array_map(fn (string $interface) => self::addContainer($interface, $class), $reflectionClass->getInterfaceNames());
        }
    }

    private static function addContainer($key, $value): void
    {
        $container = Container::instance();
        if ($container instanceof \Webman\Container) {
            $container->addDefinitions([$key => $value]);
        } elseif ($container instanceof \DI\Container) {
            $container->set($key, $value);
        }
    }
}
