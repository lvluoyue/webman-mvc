<?php

namespace app\annotation;

use app\annotation\parser\EventParser;
use LinFly\Annotation\AbstractAnnotationAttribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Event extends AbstractAnnotationAttribute
{

    public function __construct(string $name)
    {
        $this->setArguments(func_get_args());
    }

    public static function getParser(): string|array
    {
        return EventParser::class;
    }
}