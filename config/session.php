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

use Webman\Session\FileSessionHandler;
use Webman\Session\RedisSessionHandler;
use Webman\Session\RedisClusterSessionHandler;

return [

    'type' => env('SESSION_DRIVER', 'file'), // or redis or redis_cluster

    'handler' => match (env('SESSION_DRIVER', 'file')){
        'file' => FileSessionHandler::class,
        'redis' => RedisSessionHandler::class,
        'redis_cluster' => RedisClusterSessionHandler::class,
    },

    'config' => [
        'file' => [
            'save_path' => runtime_path() . env('SESSION_FILE_PATH', 'sessions'),
        ],
        'redis' => [
            'host' => env('SESSION_REDIS_HOST', '127.0.0.1'),
            'port' => env('SESSION_REDIS_PORT', 6379),
            'auth' => env('SESSION_REDIS_PASSWORD', ''),
            'timeout' => 2,
            'database' => '',
            'prefix' => env('SESSION_REDIS_PREFIX', 'redis_session_'),
        ],
        'redis_cluster' => [
            'host' => ['127.0.0.1:7000', '127.0.0.1:7001', '127.0.0.1:7001'],
            'timeout' => 2,
            'auth' => '',
            'prefix' => 'redis_session_',
        ]
    ],

    'session_name' => env('SESSION_NAME', 'PHPSID'),
    
    'auto_update_timestamp' => false,

    'lifetime' => 7*24*60*60,

    'cookie_lifetime' => 365*24*60*60,

    'cookie_path' => '/',

    'domain' => '',
    
    'http_only' => true,

    'secure' => false,
    
    'same_site' => '',

    'gc_probability' => [1, 1000],

];
