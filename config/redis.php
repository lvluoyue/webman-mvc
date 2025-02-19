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

return [
    'default' => [
        'host' => env("REDIS_HOST", '127.0.0.1'),
        'password' => env("REDIS_PASSWORD", null),
        'port' => env("REDIS_PORT", 6379),
        'database' => env("REDIS_DATABASE"),
        // Connection pool, supports only Swoole or Swow drivers.
        'pool' => [
            'max_connections' => env("REDIS_POOL_MAX_CONNECTIONS", 10),
            'min_connections' => (int)env("REDIS_POOL_MIN_CONNECTIONS", 0),
            'wait_timeout' => (float)env("REDIS_POOL_WAIT_TIMEOUT", 3),
            'idle_timeout' => (float)env("REDIS_POOL_IDLE_TIMEOUT", 60),
            'heartbeat_interval' => (float)env("REDIS_POOL_HEARTBEAT_INTERVAL", 50),
        ],
    ],
    'cache' => [
        'host' => env("CACHE_REDIS_HOST", '127.0.0.1'),
        'password' => env("CACHE_REDIS_PASSWORD", null),
        'port' => env("CACHE_REDIS_PORT", 6379),
        'database' => env("CACHE_REDIS_DATABASE", 0),
        'prefix' => env("CACHE_REDIS_PREFIX", 'webman_cache_'),
        // Connection pool, supports only Swoole or Swow drivers.
        'pool' => [
            'max_connections' => env("CACHE_POOL_MAX_CONNECTIONS", 10),
            'min_connections' => (int)env("CACHE_POOL_MIN_CONNECTIONS", 0),
            'wait_timeout' => (float)env("CACHE_POOL_WAIT_TIMEOUT", 3),
            'idle_timeout' => (float)env("CACHE_POOL_IDLE_TIMEOUT", 60),
            'heartbeat_interval' => (float)env("CACHE_POOL_HEARTBEAT_INTERVAL", 50),
        ],
    ],
];
