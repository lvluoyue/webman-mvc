<?php

namespace app\annotation;

use app\annotation\parser\BeanParser;
use LinFly\Annotation\AbstractAnnotationAttribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Bean extends AbstractAnnotationAttribute
{
    public function __construct(string $name = '', string $requireClass = '')
    {
        $this->setArguments(\func_get_args());
    }

    public static function getParser(): string|array
    {
        return BeanParser::class;
    }
}
