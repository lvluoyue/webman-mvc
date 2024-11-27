<?php

namespace app\annotation;

use LinFly\Annotation\AbstractAnnotation;

#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_CLASS_CONSTANT)]
class Value extends AbstractAnnotation
{

    /**
     * 第一个参数必须包含array类型，用来接收注解的参数
     * @param string|array $controller
     */
    public function __construct(public string|array $controller = '')
    {
        $this->paresArgs(func_get_args(), 'controller');
    }
}