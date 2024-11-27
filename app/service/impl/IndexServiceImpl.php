<?php
namespace app\service\impl;

use app\annotation\Component;
use app\service\IndexService;
use DI\Attribute\Inject;

#[Component]
class IndexServiceImpl implements IndexService
{
    #[Inject("test.abc")]
    private string $abc;

    public function index(string $v = '')
    {
//        print_r(Container::get("test.abc"));
        return $this->abc . $v;
    }
}