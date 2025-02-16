<?php

use WebmanTech\Auth\Authentication\FailureHandler\RedirectHandler;
use WebmanTech\Auth\Authentication\Method\SessionMethod;
use WebmanTech\Auth\Interfaces\IdentityInterface;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;

return [
    // 默认 guard
    'default' => 'session',
    // 多 guard 配置
    'guards' => [
        // session 的例子
        'session' => [
            'class' => WebmanTech\Auth\Guard\Guard::class,
            'identityRepository' => function () {
                return \support\Container::get(\app\common\Bean\UserManager::class);
            },
            'authenticationMethod' => function (IdentityRepositoryInterface $identityRepository) {
                // 通过 session 认证
                return new SessionMethod($identityRepository);
            },
            'authenticationFailureHandler' => function () {
                // 抛出 UnauthorizedException 异常，交给框架处理
                return new WebmanTech\Auth\Authentication\FailureHandler\ThrowExceptionHandler();
            },
        ],
        // api 接口的例子
        'api' => [
            'class' => WebmanTech\Auth\Guard\Guard::class,
            'identityRepository' => function () {
                return new class implements IdentityInterface,IdentityRepositoryInterface
                {

                    public function getId(): ?string
                    {
                        return Tinywan\Jwt\JwtToken::getCurrentId();
                    }

                    public function refreshIdentity()
                    {
                        return $this;
                    }

                    public function findIdentity(string $token, string $type = null): ?IdentityInterface
                    {
                        return $this;
                    }
                };
            },
            'authenticationMethod' => function (IdentityRepositoryInterface $identityRepository) {
                // 通过 request 请求参数授权，默认 name 为 access-token，放在 get 或 post 里都可以
//                return new WebmanTech\Auth\Authentication\Method\RequestMethod($identityRepository);
                // 使用TinywanJwt授权
                return new WebmanTech\Auth\Authentication\Method\TinywanJwtMethod($identityRepository);
            },
            'authenticationFailureHandler' => function () {
                // 响应 401 http 状态码
                //return new WebmanTech\Auth\Authentication\FailureHandler\ResponseHandler();
                // 抛出 UnauthorizedException 异常，交给框架处理
                return new WebmanTech\Auth\Authentication\FailureHandler\ThrowExceptionHandler();
            },
        ]
    ]
];