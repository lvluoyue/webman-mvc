<?php

namespace app\web\service\impl;

use app\common\Bean\UserManager;
use app\web\service\LoginService;
use DI\Attribute\Inject;
use Luoyue\WebmanMvcCore\annotation\core\Service;
use WebmanTech\Auth\Auth;

#[Service]
class LoginServiceImpl implements LoginService
{
    #[Inject]
    protected readonly UserManager $userManager;

    public function login(string $username, string $password): bool
    {
        $user = $this->userManager->findUser($username);
        if ($user && $user->password === $password) {
            Auth::guard()->login($user);

            return true;
        }

        return false;
    }

    public function logout(): void
    {
        Auth::guard()->logout();
    }
}
