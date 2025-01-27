<?php

namespace app\service\impl;

use app\service\IndexService;
use DI\Attribute\Inject;
use Luoyue\WebmanMvcCore\annotation\core\Service;

#[Service]
class IndexServiceImpl implements IndexService
{
    #[Inject('TEST_DATA')]
    private ?string $data;

    public function index(): array
    {
        return [
            'code' => 200,
            'message' => 'success',
            'data' => $this->data,
        ];
    }
}
