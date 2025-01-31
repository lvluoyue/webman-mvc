<?php

namespace app\common\exception;

use Luoyue\WebmanMvcCore\annotation\exception\ExceptionHandler;
use Webman\Http\Request;
use Webman\Http\Response;

/**
 * 全局异常处理.
 */
class GlobalExceptionHandler
{
    /**
     * 依赖注入异常.
     */
    #[ExceptionHandler(\DI\DependencyException::class)]
    public function dependencyExceptionHandler(Request $request, \Throwable $exception): Response
    {
        return json(['code' => 500, 'message' => $exception->getMessage()]);
    }

    /**
     * 数据库查询异常.
     */
    #[ExceptionHandler(\Illuminate\Database\QueryException::class)]
    public function queryExceptionHandler(Request $request, \Throwable $exception): Response
    {
        return json(['code' => 500, 'message' => env('server_app_debug', true) ? $exception->getMessage() : '数据库查询异常']);
    }

    /**
     * 处理基于Exception的异常(用于异常兜底).
     */
    #[ExceptionHandler(\Exception::class)]
    public function exceptionHandler(Request $request, \Throwable $exception): Response
    {
        return json(['code' => 500, 'message' => $exception->getMessage(), 'file' => $exception->getFile(), 'line' => $exception->getLine(), 'exception_class' => $exception::class]);
    }
}
