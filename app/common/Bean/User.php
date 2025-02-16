<?php

namespace app\common\Bean;

use Casbin\WebmanPermission\Permission;
use WebmanTech\Auth\Interfaces\IdentityInterface;

class User implements IdentityInterface
{
    public function __construct(public int $id, public string $username, public string $password, public array $roles)
    {
        foreach ($roles as $role) {
            Permission::addRoleForUser((string) $id, $role);
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function refreshIdentity()
    {
        return $this;
    }
}
