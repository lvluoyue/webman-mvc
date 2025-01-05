<?php

namespace app\annotation;

/**
 * Service注解
 * 告诉容器，这个类是服务层，
 * 然后他会根据这个类的interface实例化，并注入到容器中
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Service
{
    public function __construct()
    {
    }
}