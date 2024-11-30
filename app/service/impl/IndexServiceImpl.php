<?php
namespace app\service\impl;

use app\annotation\Component;
use app\service\IndexService;
use DI\Attribute\Inject;
use support\Db;

#[Component]
class IndexServiceImpl implements IndexService
{
    #[Inject("test.abc")]
    private string $abc;

    public function index(string $v = '')
    {
//        print_r(Container::get("test.abc"));
        return json([
            'code' => 200,
            'message' => 'success',
            'data' => 'Hello World'
        ]);
    }

    public function mysql()
    {
        return Db::connection('mysql')->table("user")->get();
    }

    function php()
    {
        $str = "echo 1;";
        $str = str_replace("\"", "\\\"", $str);
        return exec("docker run --rm php php -r \"{$str}\"");
    }
}