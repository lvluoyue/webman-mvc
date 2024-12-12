
# webman-mvc

## 介绍

 > webman-mvc 是一个基于 webman 的 mvc 框架，使用composer现有的生态开发，代码规范参考了java的springboot框架，开发环境推荐使用 phpstorm 开发。

## 特性
  - 支持env配置（vlucas/phpdotenv）
  - 支持协程开发（workbunny/webman-coroutine）
  - 支持注解开发（linfly/annotation）
  - 支持依赖注入（php-di/php-di）
  - 支持控制反转
  - 支持单元测试（phpunit/phpunit）
  - 支持数据库ORM（illuminate/database）

## 安装
  - 开发环境安装
  ```shell
  composer install
  ```
  - 生产环境安装
  ```shell
  composer install --no-dev
  ```

## 启动
  - windows环境
  ```shell
  php windows.php
  ```
  - linux环境
  ```shell
  php start.php start -d
  ```
  - docker环境（镜像）
  ```shell
  # 启动容器
  docker run -d -v E:\workerman\test:/opt -p 8787:8787 luoyueapi/webman-mvc
  ```
- docker环境（自定义构建）

  在项目根目录下创建Dockerfile文件，内容如下：

  ```dockerfile
  FROM php:8.3-cli
  
  # 安装Composer，用于开发环境
  #RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
  COPY --from=composer /usr/bin/composer /usr/bin/
  
  # 安装必要依赖
  RUN apt-get update && apt-get install -y libssl-dev libcurl4-openssl-dev libpq-dev libzip-dev libbz2-dev \
  libwebp-dev libjpeg-dev libpng-dev libfreetype6-dev libvpx-dev unzip libevent-dev
  
  # 安装php扩展
  RUN docker-php-ext-install pcntl pdo_mysql pdo_pgsql sockets zip bz2 gd
  RUN pecl install redis
  RUN pecl install -D 'enable-openssl="yes" enable-swoole-curl="yes" enable-http2="yes" enable-swoole-thread="yes"' swoole
  
  # 设置挂载点
  VOLUME ["/opt"]
  
  # 设置工作目录
  WORKDIR /opt
  
  # 运行webman
  ENTRYPOINT ["php", "-d", "extension=swoole", "start.php", "start"]
  ```

  然后运行如下代码进行构建：

  ```shell
  # 构建镜像
  docker build -t webman-mvc .
  # 启动容器
  docker run -d -v E:\workerman\test:/opt -p 8787:8787 webman-mvc
  ```
  Tips：在使用phpstorm开发时，可直接使用运行配置启动webman-mvc。

## 目录结构
```
├── app                           应用目录
│   ├── annotation                注解目录
│   ├── controller                控制器目录
│   ├── service                   服务目录
│   ├── model                     模型目录
│   ├── view                      视图目录
│   ├── middleware                中间件目录
│   │   └── StaticFile.php        自带静态文件中间件
│   ├── process                   自定义进程目录
│   │   ├── Http.php              Http进程
│   │   └── Monitor.php           监控进程
│   └── functions.php             业务自定义函数写到这个文件里
├── config                        配置目录
│   ├── app.php                   应用配置
│   ├── autoload.php              这里配置的文件会被自动加载
│   ├── bootstrap.php             进程启动时onWorkerStart时运行的回调配置
│   ├── container.php             容器配置
│   ├── dependence.php            容器依赖配置
│   ├── database.php              数据库配置
│   ├── exception.php             异常配置
│   ├── log.php                   日志配置
│   ├── middleware.php            中间件配置
│   ├── process.php               自定义进程配置
│   ├── redis.php                 redis配置
│   ├── route.php                 路由配置
│   ├── server.php                端口、进程数等服务器配置
│   ├── view.php                  视图配置
│   ├── static.php                静态文件开关及静态文件中间件配置
│   ├── translation.php           多语言配置
│   └── session.php               session配置
├── public                        静态资源目录
├── runtime                       应用的运行时目录，需要可写权限
├── tests                         单元测试目录
├── .env                          环境配置文件
├── start.php                     服务启动文件
├── Dockerfile                    docker镜像构建文件
├── vendor                        composer安装的第三方类库目录
└── support                       类库适配(包括第三方类库)
    ├── Request.php               请求类
    ├── Response.php              响应类
    ├── helpers.php               助手函数(业务自定义函数请写到app/functions.php)
    └── bootstrap.php             进程启动后初始化脚本
```

## env配置
 - 所有配置项都可以通过.env文件配置。在docker环境中可在运行时指定环境变量，如：`docker run -e SERVER_APP_DEBUG=true webman-mvc`
 - 配置项可自定义，具体使用请看vlucas/phpdotenv或webman官方文档
 - 框架配置项如下：

```ini
# 应用名称
SERVER_APP_NAME=webman
# 是否开启debug模式
SERVER_APP_DEBUG=true

# 监听地址
SERVER_APP_ADDRESS=0.0.0.0
# 监听端口
SERVER_APP_PROT=8787
# 进程数
SERVER_APP_PROCESS=40
# 时区
SERVER_APP_TIMEZONE=Asia/Shanghai
# 控制器后缀
SERVER_APP_CONTROLLER_SUFFIX=Controller
# 是否启用路由复用
SERVER_APP_CONTROLLER_REUSE=true

# 默认数据库
DATABASE_DEFAULT=pgsql
# mysql地址
DATABASE_CONNECTIONS_MYSQL_HOST=127.0.0.1
# mysql端口
DATABASE_CONNECTIONS_MYSQL_PORT=3306
# 数据库名
DATABASE_CONNECTIONS_MYSQL_DATABASE=db
# 数据库用户名
DATABASE_CONNECTIONS_MYSQL_USERNAME=root
# 数据库密码
DATABASE_CONNECTIONS_MYSQL_PASSWORD=123456

# pgsql地址
DATABASE_CONNECTIONS_PGSQL_HOST=127.0.0.1
# pgsql端口
DATABASE_CONNECTIONS_PGSQL_PORT=5432
# 数据库名
DATABASE_CONNECTIONS_PGSQL_DATABASE=postgres
# 数据库用户名
DATABASE_CONNECTIONS_PGSQL_USERNAME=postgres
# 数据库密码
DATABASE_CONNECTIONS_PGSQL_PASSWORD=123456
# 数据库schema
DATABASE_CONNECTIONS_PGSQL_SCHEMA=postgres

# sqlite地址
DATABASE_CONNECTIONS_SQLITE_PATH=/database.sqlite
```
