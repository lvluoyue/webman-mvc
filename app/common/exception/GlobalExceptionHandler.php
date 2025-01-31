<?php

namespace app\common\exception;

use Luoyue\WebmanMvcCore\annotation\exception\ExceptionHandler;
use Webman\Http\Request;
use Webman\Http\Response;

/**
 */
class GlobalExceptionHandler
{
    /**
     */
    #[ExceptionHandler(\DI\DependencyException::class)]
    public function dependencyExceptionHandler(Request $request, \Throwable $exception): Response
    {
        return json(['code' => 500, 'message' => $exception->getMessage()]);
    }

    /**
     */
    #[ExceptionHandler(\Illuminate\Database\QueryException::class)]
    public function queryExceptionHandler(Request $request, \Throwable $exception): Response
    {
    }

    /**
     */
    #[ExceptionHandler(\Exception::class)]
    public function exceptionHandler(Request $request, \Throwable $exception): Response
    {
    }
}
