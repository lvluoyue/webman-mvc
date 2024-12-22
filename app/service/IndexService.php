<?php

namespace app\service;

use support\Response;
use Workerman\Protocols\Http\Chunk;

interface IndexService {
    function index(): Response;
}