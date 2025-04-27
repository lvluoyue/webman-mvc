<?php

return [
    'enable' => true,
    'proxyPath' => '/runtime/cache/aop',
    // 切入点扫描路径，不建议扫描vendor目录
    'scans' => [
        'app',
    ],
    // 忽略的进程名称
    'ignore_process' => [
        'monitor',
    ],
    // 切入的切面类
    'aspect' => [
    ],
];
