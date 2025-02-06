<?php

namespace app\common\Bean;

use Casbin\WebmanPermission\Permission;
use Luoyue\WebmanMvcCore\annotation\core\Bean;
use Luoyue\WebmanMvcCore\handler\bean\AbstractUser;
use Luoyue\WebmanMvcCore\handler\PermissionHandler;

class UserConfig
{
//    #[Bean(requireClass: PermissionHandler::class)]
//    public function permissionHandler(): PermissionHandler
//    {
//        $permissionHandler = new PermissionHandler();
//        $permissionHandler->addUser(new AbstractUser(2, 'xiaoxin', password_hash('123456', \PASSWORD_DEFAULT)));
//
//         Permission::addRoleForUser('2', 'index');
//        return $permissionHandler;
//    }
}
