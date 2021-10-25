<?php

namespace App\Contexts\Auth\Application\Commands;

final class IssueToken implements IssueTokenCommandInterface
{
    private function __construct(
        private string $identity,
        private string $password,
        private string $deviceName,
    ) {
    }

    public static function of(string $identity, string $password, string $deviceName): self
    {
        return new self($identity, $password, $deviceName);
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDeviceName(): string
    {
        return $this->deviceName;
    }
}