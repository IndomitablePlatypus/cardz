<?php

namespace App\Contexts\MobileAppBack\Integration\Contracts;

interface AuthContextInterface
{
    public function registerUser(?string $email, ?string $phone, string $name, string $password, string $deviceName): string;

    public function getToken(string $identity, string $password, string $deviceName): string;
}
