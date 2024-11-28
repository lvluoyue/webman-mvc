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
    'default' => env('database.default', 'mysql'),

    // 各种数据库配置
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('database.connections.mysql.host', '127.0.0.1'),
            'port' => env('database.connections.mysql.port', 3306),
            'database' => env('database.connections.mysql.database', 'test'),
            'username' => env('database.connections.mysql.username', 'root'),
            'password' => env('database.connections.mysql.password', ''),
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
            'host' => env('database.connections.pgsql.host', '127.0.0.1'),
            'port' => env('database.connections.pgsql.port', 5432),
            'database' => env('database.connections.pgsql.database', 'test'),
            'username' => env('database.connections.pgsql.username', 'root'),
            'password' => env('database.connections.pgsql.password', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => env('database.connections.pgsql.schema', 'public'),
            'sslmode' => 'prefer',
            'application_name' => env('APP_NAME', 'API-Server'),
        ],
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => base_path(false) . env('SQLITE_PATH', '/database.sqlite'),
            'prefix' => '',
        ],
    ],
];