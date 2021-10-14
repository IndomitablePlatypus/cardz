<?php

namespace App\Contexts\Auth\Domain\Model\User;

use App\Shared\Contracts\Domain\ValueObjectInterface;
use Illuminate\Support\Facades\Hash;

final class Password implements ValueObjectInterface
{
    private function __construct(
        private string $passwordHash,
    ) {
    }

    public static function of(string $password): self
    {
        return new self(Hash::make($password));
    }

    public static function ofHash(string $passwordHash): self
    {
        return new self($passwordHash);
    }

    public function __toString(): string
    {
        return $this->passwordHash;
    }

    public function toArray(): array
    {
        return [
            'hash' => $this->passwordHash,
        ];
    }
}
