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
    // 默认数据库
    'default' => env('DATABASE_DEFAULT', 'mysql'),
//    'persistent' => false,
    // 各种数据库配置
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DATABASE_CONNECTIONS_MYSQL_HOST', '127.0.0.1'),
            'port' => env('DATABASE_CONNECTIONS_MYSQL_PORT', 3306),
            'database' => env('DATABASE_CONNECTIONS_MYSQL_DATABASE', 'test'),
            'username' => env('DATABASE_CONNECTIONS_MYSQL_USERNAME', 'root'),
            'password' => env('DATABASE_CONNECTIONS_MYSQL_PASSWORD', ''),
            'unix_socket' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'options' => [
                //\PDO::ATTR_TIMEOUT => 3
            ]
        ],
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DATABASE_CONNECTIONS_PGSQL_HOST', '127.0.0.1'),
            'port' => env('DATABASE_CONNECTIONS_PGSQL_PORT', 5432),
            'database' => env('DATABASE_CONNECTIONS_PGSQL_DATABASE', 'test'),
            'username' => env('DATABASE_CONNECTIONS_PGSQL_USERNAME', 'root'),
            'password' => env('DATABASE_CONNECTIONS_PGSQL_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => env('DATABASE_CONNECTIONS_PGSQL_SCHEMA', 'public'),
            'sslmode' => 'prefer',
            'application_name' => env('SERVER_APP_NAME', 'webman'),
        ],
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => base_path(false) . env('DATABASE_CONNECTIONS_SQLITE_PATH', '/database.sqlite'),
            'prefix' => '',
        ],
    ],
];