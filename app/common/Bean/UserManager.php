<?php

namespace app\common\Bean;

use WebmanTech\Auth\Interfaces\IdentityInterface;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;

class UserManager implements IdentityRepositoryInterface
{
    /** @var User[] */
    private array $users = [];

    public function addUser(User ...$users): void
    {
        foreach ($users as $user) {
            $this->users[$user->getId()] = $user;
        }
    }

    public function findIdentity(string $token, ?string $type = null): ?IdentityInterface
    {
        return $this->users[$token] ?? null;
    }

    public function findUser(string $username): ?User
    {
        foreach ($this->users as $user) {
            if ($user->username === $username) {
                return $user;
            }
        }

        return null;
    }
}
