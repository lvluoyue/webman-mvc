<?php

namespace app\annotation\handle;

use LinFly\Annotation\Interfaces\IAnnotationHandle;
use ReflectionClass;
use support\Container;

class ComponentHandle implements IAnnotationHandle
{
    public static function handle(array $item): void
    {
        $ref = new ReflectionClass($item['class']);
        foreach ($ref->getInterfaces() as $interface) {
            Container::set($interface->name, \DI\get($item['class']));
        }
    }
}