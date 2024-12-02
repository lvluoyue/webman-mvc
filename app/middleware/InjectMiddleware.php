<?php

namespace app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

class InjectMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {

        $response = $handler($request);
        return $response;
    }
}