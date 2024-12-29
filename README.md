
# webman-mvc

## 介绍

 > webman-mvc 是一个基于 webman 的 mvc 框架，使用composer现有的生态开发，代码规范参考了java的spring框架，开发环境推荐使用 phpstorm 开发。

## 特性
  - 支持env配置（[vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)）
  - 支持协程开发（[workbunny/webman-coroutine](https://github.com/workbunny/webman-coroutine)）
  - 支持注解开发（[linfly/annotation](https://github.com/imlinfly/webman-annotation)）
  - 支持验证器（[topthink/think-validate](https://github.com/top-think/think-validate)）
  - 支持依赖注入（[php-di/php-di](https://github.com/PHP-DI/PHP-DI)）
  - 支持控制反转
  - 支持单元测试（[phpunit/phpunit](https://github.com/sebastianbergmann/phpunit)）
  - 支持数据库ORM（illuminate/database）

## 安装
  - 开发环境安装
  ```shell
  composer install --optimize-autoloader --classmap-authoritative
  ```
  - 生产环境安装
  ```shell
     composer install --no-dev --optimize-autoloader --classmap-authoritative
  ```

## 启动
  ### windows环境
  ```shell
  php windows.php
  ```
  ### linux环境
  ```shell
  php start.php start -d
  ```
  ### 二进制启动
  - 请移步至相关[项目仓库](https://github.com/walkor/static-php-cli)查看。
  ### docker环境
  - 使用官方镜像
  ```shell
  # 启动容器
  docker run -d -v E:\workerman\test:/opt -p 8787:8787 luoyueapi/webman-mvc
  ```

  - 自定义构建镜像
  
  在项目根目录下创建Dockerfile文件，内容如下：

  ```dockerfile
  FROM php:8.3-zts
  
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
  Tips：在使用`phpstorm`开发时，可直接使用运行配置启动`webman-mvc`。

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
├── vendor                        composer安装的第三方类库目录
└── support                       类库适配(包括第三方类库)
    ├── Request.php               请求类
    ├── Response.php              响应类
    ├── helpers.php               助手函数(业务自定义函数请写到app/functions.php)
    └── bootstrap.php             进程启动后初始化脚本
```

## env配置
 - 所有配置项都可以通过.env文件配置。在docker环境中可在运行时指定环境变量，如：`docker run -e SERVER_APP_DEBUG=true webman-mvc`。
 - 配置项可自定义，具体使用请看vlucas/phpdotenv或webman官方文档。
 - 系统默认配置项在.env.example中，并且是默认值。如需更改请执行如下命令。
 - 更多请查看[官方文档](https://github.com/vlucas/phpdotenv)。

```shell
cp .env.example .env
```

## 注解处理
  - 注解使用的是[linfly/annotation](https://github.com/imlinfly/webman-annotation),官方文档仅适用于1.x版本，仅供参考。
  - 控制器的文件名的后缀在webman中是需要与配置文件`SERVER_APP_CONTROLLER_SUFFIX`保持一致，但使用注解模式时，则不需要。 不过为了项目规范，建议使用控制器后缀。
### 控制器注解
  - 控制器类必须包含`#[Controller]`注解，如：`#[Controller("/api")]`
  - 方法必须包含方法注解, 如：`#[GetMapping("index")]`
  - 示例:在`app/controller`中新建文件`TestController.php`如下代码
```php
<?php

namespace app\controller;

use LinFly\Annotation\Attributes\Route\GetMapping;
use LinFly\Annotation\Attributes\Route\Controller;
use support\Request;
use support\Response;

#[Controller("/api")]
class TestController
{
    //GET http://127.0.0.1:8787/api/index
    #[GetMapping("index")]
    public function index(Request $request): Response
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }
}
```

### 命名空间注解
  - 控制器类必须包含`#[NamespaceController]`注解，如：`#[NamespaceController(namespace: "app\controller")]`,中间为删除类名中的命名空间前缀后的作为路径，替换后为路由地址。
  - 使用__NAMESPACE__作为参数，则自动将当前类名做为路由地址。
  - 示例:在`app/controller`中新建文件`TestController.php`如下代码
```php
<?php

namespace app\controller;

use LinFly\Annotation\Attributes\Route\GetMapping;
use LinFly\Annotation\Attributes\Route\NamespaceController;
use support\Request;
use support\Response;

#[NamespaceController(namespace: __NAMESPACE__)]
class TestController
{
    //GET http://127.0.0.1:8787/test/index
    #[GetMapping("index")]
    public function index(Request $request): Response
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }
}
```

### 方法路由注解
 - 可使用的注解: `RequestMapping`, `GetMapping`, `PostMapping`, `PutMapping`, `DeleteMapping`, `PatchMapping`, `HeadMapping`, `OptionsMapping`
 - 关于路径参数: 
   - `#[RequestMapping]`, 不带参数，则使用方法名作为路由地址
   - `#[RequestMapping("")]`, 则使用控制器前缀作为路由器地址~~~~
   - `#[RequestMapping("/api")]`: 使用根路径，如`/api`
   - `#[RequestMapping("api")]`: 使用控制器前缀+路径参数，如`#[Controller("/test")]`注解路径为`/test/api`
   - `#[RequestMapping("/api/{id}")]`: 使用路由参数，如`/api/abc`
   - `#[RequestMapping("/api/{id:\d+}")]`: 使用正则表达式约束路由参数，如`/api/123`

### 中间件注解
- `#[Middleware]`注解可作用在类和方法上，在类上注解则作用在类中的所有方法上，在方法上注解则作用在当前方法上。
- 注解可使用webman官方的注解也可使用linfly/annotation注解。
```php
<?php
namespace app\controller;

use Webman\Annotation\Middleware;
//use LinFly\Annotation\Attributes\Route\Middleware;
use LinFly\Annotation\Attributes\Route\Controller;

#[Controller("/code")]
#[Middleware(\app\middleware\CrossDomain::class)]
class TestController
{

    #[GetMapping("index")]
    public function index(Request $request): Response
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
```

### 验证器注解
 - 验证器处理器使用[thinkphp](https://github.com/top-think/think-validate)中的验证器，使用`#[Validate]`注解即可。
 - params参数为传入的变量,validate为验证器类名,如:`#[Validate(params: ["$get"], validate: \app\validate\TestValidate::class)]`
 - 此注解同样可作用在类和方法上。
```php
<?php
#[Controller("/code")]
#[Validate(params: '$post', validate: TestValidate::class)]
class TestController
{

    #[Inject]
    private readonly CodeService $codeService;

    #[PostMapping]
    public function index(Request $request): Response
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
```

## 依赖注入
- 相关文档:[php-di/php-di](https://github.com/PHP-DI/PHP-DI)，此项目使用`#[Inject]`注解即可。
- 注解使用方式: `#[Inject]`注解可作用在`成员变量、成员方法、控制器方法`的形参上，在类上注解则作用在类中的所有方法上，在方法上注解则作用在当前方法上。
```php
<?php
// app/controller/IndexController.php
namespace app\controller;

use app\service\IndexService;
use DI\Attribute\Inject;
use LinFly\Annotation\Attributes\Route\RequestMapping;

class IndexController
{
    #[Inject]
    private readonly IndexService $indexService;

    #[RequestMapping("")]
    public function index(Request $request): Response
    {
        return $this->indexService->index();
    }
}
```

```php
<?php
// app/service/IndexService.php
namespace app\service;

interface IndexService {
    function index(): array;
}
```

```php
<?php
// app/service/impl/IndexServiceImpl.php
namespace app\service;

use app\annotation\Service;
use app\service\IndexService;

#[Service]
class IndexServiceImpl implements IndexService
{

    public function index(): array
    {
        return [
            'code' => 200,
            'message' => 'success',
            'data' => 'hello world'
        ];
    }

}
```

