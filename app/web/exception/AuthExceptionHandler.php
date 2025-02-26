<?php

namespace app\web\exception;

use Luoyue\WebmanMvcCore\annotation\exception\ExceptionHandler;
use Luoyue\WebmanMvcCore\exception\PermissionException;
use Webman\Http\Request;
use Webman\Http\Response;
use WebmanTech\Auth\Exceptions\UnauthorizedException;

class AuthExceptionHandler
{
    /**
     * 用户未登录.
     */
    #[ExceptionHandler(UnauthorizedException::class, 'web')]
    public function userExceptionHandler(Request $request, \Throwable $exception): Response
    {
        return response(<<<'HTML'
            <!DOCTYPE html>
            <html lang="en">
              <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <meta name="description" content="">
                <meta name="author" content="">
                <title>Please sign in</title>
                <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
                <link href="https://getbootstrap.com/docs/4.0/examples/signin/signin.css" rel="stylesheet" integrity="sha384-oOE/3m0LUMPub4kaC09mrdEhIc+e3exm4xOGxAmuFXhBNF4hcg/6MiAXAf5p0P56" crossorigin="anonymous"/>
              </head>
              <body>
                 <div class="container">
                  <form class="form-signin" method="post" action="/api/login">
                    <h2 class="form-signin-heading">Please sign in</h2>
                    <p>
                      <label for="username" class="sr-only">Username</label>
                      <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
                    </p>
                    <p>
                      <label for="password" class="sr-only">Password</label>
                      <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    </p>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                  </form>
            </div>
            </body></html>
            HTML);
        //        return json(['code' => 500, 'message' => '您当前未登录']);
    }

    /**
     * 用户无权限.
     */
    #[ExceptionHandler(PermissionException::class, 'web')]
    public function exceptionHandler(Request $request, \Throwable $exception): Response
    {
        return response(<<<HTML
            <!DOCTYPE html>
            <html lang="en">
                <body>
                    <h3>{$exception->getMessage()}</h3>
                    <button type="submit" onclick="window.location.href='/api/logout'">logout</button>
            </body></html>
            HTML);
    }
    //        return json(['code' => 500, 'message' => '您没有权限访问']);
}
