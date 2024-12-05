<?php

namespace app\service;

use support\Response;

interface IndexService {
    function index(string $v): Response;

    function mysql(): Response;

    function php(string $str): Response;

    function java(string $code, string $input): Response;
}