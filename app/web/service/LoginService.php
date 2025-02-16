<?php

namespace app\web\service;

interface LoginService
{
    public function login(string $username, string $password): bool;

    public function logout(): void;
}
