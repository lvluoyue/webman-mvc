<?php

namespace app\service;

use support\Response;
use Workerman\Protocols\Http\Chunk;

interface IndexService {
    function index(string $v): Response;

    function sse(): Response;

    function chunked(): Response;

    function mysql(): Response;

    function php(string $str): Response;

    function java(string $code, string $input): Response;
}