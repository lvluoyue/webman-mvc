<?php

namespace app\annotation\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use ReflectionMethod;
use support\Container;

class BeanParser implements IAnnotationParser
{
    public static function process(array $item): void
    {
        if (!class_exists($item['parameters']['requireClass'])) {
            return;
        }
        $reflectionMethod = new ReflectionMethod($item['class'], $item['method']);
        $returnType = $reflectionMethod->getReturnType();
        if (!$returnType && !$item['parameters']['name']) {
            echo "[Annotation:Bean] {$item['class']}::{$item['method']} return type is null\n";

            return;
        }
        $key = $item['parameters']['name'] ?: $returnType?->getName();
        if (!$key) {
            echo "[Bean] {$item['class']}::{$item['method']} return type is null\n";

            return;
        }
        $container = Container::instance();
        if ($container instanceof \Webman\Container) {
            $container->addDefinitions([$key => $reflectionMethod->getClosureThis()]);
        } elseif ($container instanceof \DI\Container) {
            $container->set($key, \DI\factory([$item['class'], $item['method']]));
        }
    }
}
