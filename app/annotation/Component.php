<?php

namespace app\annotation;

use LinFly\Annotation\AbstractAnnotation;

/**
 * 控制反转（IOC）注解
 * 用于将某个类手动注入到容器中
 * 这对于面向接口注入有很大的帮助，因为DI并不知道具体使用哪个实现类
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Component extends AbstractAnnotation
{
    public function __construct()
    {
        $this->paresArgs(func_get_args(), 'controller');
    }
}