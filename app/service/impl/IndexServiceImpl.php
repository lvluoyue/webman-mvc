<?php
namespace app\service\impl;

use app\annotation\Component;
use app\service\IndexService;
use DI\Attribute\Inject;
use DI\Attribute\Injectable;
use LinFly\Annotation\Annotation\Bean;
use support\Container;
use support\Db;

#[Component]
//#[Bean("index")]
class IndexServiceImpl implements IndexService
{
    #[Inject("TEST_ABC")]
    private string $abc;

    public function index(string $v)
    {
        return json([
            'code' => 200,
            'message' => 'success',
            'data' => "IndexServiceImpl::abc=" . $this->abc . ',inject abcd=' . $v,
        ]);
    }

    public function mysql()
    {
        return Db::connection('mysql')->table("user")->get();
    }

    function php(string $str)
    {
        $str = base64_decode($str);
        $str = str_replace("\"", "\\\"", $str);
        return exec("docker run --rm php php -r \"{$str}\"");
    }
}