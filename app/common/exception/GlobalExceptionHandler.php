<?php

namespace app\common\exception;

use Luoyue\WebmanMvcCore\annotation\exception\ExceptionHandler;
use Webman\Http\Request;
use Webman\Http\Response;

/**
 * 全局异常处理
 */
class GlobalExceptionHandler
{
    /**
     * 依赖注入异常
     * @param Request $request
     * @param \Throwable $exception
     * @return Response
     */
    #[ExceptionHandler(\DI\DependencyException::class)]
    public function dependencyExceptionHandler(Request $request, \Throwable $exception): Response
    {
        return json(['code' => 500, 'message' => $exception->getMessage()]);
    }

    /**
     * 数据库查询异常
     * @param Request $request
     * @param \Throwable $exception
     * @return Response
     */
    #[ExceptionHandler(\Illuminate\Database\QueryException::class)]
    public function queryExceptionHandler(Request $request, \Throwable $exception): Response
    {
        return json(['code' => 500, 'message' => env("server_app_debug", true) ? $exception->getMessage() : '数据库查询异常']);
    }

    /**
     * 处理基于Exception的异常(用于异常兜底)
     * @param Request $request
     * @param \Throwable $exception
     * @return Response
     */
    #[ExceptionHandler(\Exception::class)]
    public function exceptionHandler(Request $request, \Throwable $exception): Response
    {
        return json(['code' => 500, 'message' => $exception->getMessage(), 'exception_class' => get_class($exception)]);
    }
}
