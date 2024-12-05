<?php

namespace app\annotation;


#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_CLASS_CONSTANT)]
class Bean
{

    /**
     * 第一个参数必须包含array类型，用来接收注解的参数
     * @param string|array $controller
     */
    public function __construct(public string|array $controller = '')
    {
    }
}