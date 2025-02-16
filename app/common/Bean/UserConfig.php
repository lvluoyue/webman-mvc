<?php

namespace app\common\Bean;

use Luoyue\WebmanMvcCore\annotation\core\Bean;

class UserConfig
{
    #[Bean(requireClass: UserManager::class)]
    public function permissionHandler(): UserManager
    {
        $userManager = new UserManager();
        $users = [
            new User(1, 'admin', '123456', ['admin']),
            new User(2, 'user', '123456', ['index']),
        ];
        $userManager->addUser(...$users);

        return $userManager;
    }
}
