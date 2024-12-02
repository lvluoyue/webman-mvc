<?php
namespace app\bootstrap;

use app\annotation\Component;
use app\annotation\handle\ComponentHandle;
use app\annotation\handle\ValueHandle;
use app\annotation\Bean;
use LinFly\Annotation\Annotation;
use LinFly\Annotation\Annotation\Inject;
use Webman\Bootstrap;

class CreateAnnotationHandle implements Bootstrap
{
    /**
     * start
     * @access public
     * @param $worker
     * @return void
     */
    public static function start($worker)
    {
        // monitor进程不执行
        if ($worker?->name === 'monitor') {
            return;
        }

        // 添加IOC注解类处理器
        Annotation::addHandle(Component::class, ComponentHandle::class);
        // 添加Value注解类处理器
        Annotation::addHandle(Bean::class, ValueHandle::class);
    }
}