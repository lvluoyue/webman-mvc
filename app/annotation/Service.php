<?php

namespace app\annotation;

/**
 * 控制反转（IOC）注解
 * 用于将某个对象手动注入到容器中
 * 这对于面向接口注入有很大的帮助，因为DI并不知道具体使用哪个实现类
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Service
{
    public function __construct()
    {
    }
}