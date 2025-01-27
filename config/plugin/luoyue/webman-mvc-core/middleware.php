<?php

return [
    "@" => [
        \Luoyue\WebmanMvcCore\middleware\HasRoleMiddleware::class,
        \Luoyue\WebmanMvcCore\middleware\HasPermiMiddleware::class
    ]
];