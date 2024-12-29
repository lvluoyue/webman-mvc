<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
if (!function_exists('getAnnotation')) {
    function getAnnotation(string $attributeClassName): array
    {
        $dirIterator = new \RecursiveDirectoryIterator(app_path());
        $iterator = new \RecursiveIteratorIterator($dirIterator);
        $result = [];
        foreach ($iterator as $file) {
            // 忽略非PHP文件
            if ($file->getExtension() != 'php') {
                continue;
            }

            // 根据文件路径获取类名
            $className = str_replace(
                '/',
                '\\',
                substr(substr($file->getPathname(), strlen(base_path())), 0, -4)
            );

            if (!class_exists($className)) {
                continue;
            }

            $controller = new \ReflectionClass($className);
            $controllerClass = $controller->getAttributes($attributeClassName);
            if (!isset($controllerClass[0])) {
                continue;
            }
            $result[] = $controller;
        }
        return $result;
    }
}
$dependencies = [...$_ENV];
$compoents = getAnnotation(\app\annotation\Service::class);

/** @var \ReflectionClass $reflectionClass */
foreach ($compoents as $reflectionClass) {
    foreach ($reflectionClass->getInterfaces() as $interface) {
        $dependencies[$interface->name] = \DI\get($reflectionClass->name);
    }
}
return $dependencies;