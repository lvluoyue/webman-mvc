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
$dependencies = [...$_ENV];
$compoents = getAnnotation(\app\annotation\Component::class);

/** @var \ReflectionClass $reflectionClass */
foreach ($compoents as $reflectionClass) {
    foreach ($reflectionClass->getInterfaces() as $interface) {
        $dependencies[$interface->name] = \DI\get($reflectionClass->name);
    }
}
return $dependencies;