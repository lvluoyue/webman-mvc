<?php

namespace app\exception;

use DI\NotFoundException;
use Exception;
use Luoyue\WebmanMvcCore\annotation\exception\ExceptionHandler;
use Webman\Http\Request;
use Webman\Http\Response;

class Handler
{
    #[ExceptionHandler(Exception::class)]
    public function exceptionHandler(Request $request, \Throwable $exception): Response
    {
        return json(['code' => 500, 'message' => $exception->getMessage()]);
    }
    #[ExceptionHandler(\DI\DependencyException::class)]
    public function NotFoundHandler(Request $request, \Throwable $exception): Response
    {
        return json(['code' => 501, 'message' => $exception->getMessage()]);
    }
}
