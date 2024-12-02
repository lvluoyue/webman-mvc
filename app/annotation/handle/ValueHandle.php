<?php

namespace app\annotation\handle;

use LinFly\Annotation\Interfaces\IAnnotationHandle;

class ValueHandle implements IAnnotationHandle
{

    public static function handle(array $item): void
    {
        print_r($item);
    }
}