<?php

namespace app\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

// 跨域
class CrossDomain implements MiddlewareInterface
{
    public function process(Request $request, callable $next) : Response
    {
        //        p(getTime() . self::class);
        // 如果是options请求则返回一个空响应，否则继续向洋葱芯穿越，并得到一个响应
        $response = strtoupper($request->method()) === 'OPTIONS' ? response('', 204) : $next($request);
        // 给响应添加跨域相关的http头
        $response->withHeaders([
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Origin' => $request->header('origin', '*'),
            'Access-Control-Allow-Methods' => $request->header('access-control-request-method', '*'),
            'Access-Control-Allow-Headers' => $request->header('access-control-request-headers', '*'),
        ]);

        return $response;
    }
}
