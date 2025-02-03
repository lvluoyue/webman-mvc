<?php

namespace app\web\service\impl;

use app\web\service\IndexService;
use DI\Attribute\Inject;
use Luoyue\WebmanMvcCore\annotation\core\Service;

#[Service]
class IndexServiceImpl implements IndexService
{
    #[Inject('TEST_DATA')]
    private ?string $data;

    public function json(): array
    {
        return [
            'code' => 200,
            'message' => 'success',
            'data' => $this->data,
        ];
    }
}
