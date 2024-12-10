<?php

namespace app\exception;

use support\Log;
use Webman\Http\Request;
use Webman\Http\Response;
use Workerman\Worker;

class Handler extends \support\exception\Handler
{

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     */
    public function render(Request $request, \Throwable $exception): Response
    {
        if (method_exists($exception, 'render') && ($response = $exception->render($request))) {
            return $response;
        }
        $code = $exception->getCode();
        $json = ['code' => $code ?: 500, 'msg' => $this->debug ? $exception->getMessage() : 'Server internal error'];
        if($this->debug) {
            $json['traces'] = (string)$exception;
//            debug_print_backtrace();
        }
        return new Response(200, ['Content-Type' => 'application/json'],
            json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

}