<?php

namespace Cardz\Generic\Identity\Tests\Support\Builders;

use Carbon\Carbon;
use Cardz\Generic\Identity\Domain\Model\User\User;
use Cardz\Generic\Identity\Domain\Model\User\UserId;
use Codderz\Platypus\Infrastructure\Tests\BaseBuilder;
use Illuminate\Support\Facades\Hash;

final class UserBuilder extends BaseBuilder
{
    public string $userId;

    public string $name;

    public ?string $email = null;

    public ?string $phone = null;

    public string $plainTextPassword;

    public string $password;

    public ?string $rememberToken = null;

    public ?Carbon $registrationInitiated = null;

    public ?Carbon $emailVerified = null;

    public function build(): User
    {
        return User::restore(
            $this->userId,
            $this->email,
            $this->phone,
            $this->name,
            $this->password,
            $this->rememberToken,
            $this->registrationInitiated,
            $this->emailVerified,
        );
    }

    public function generate(): static
    {
        $this->userId = UserId::makeValue();
        $this->email = $this->faker->email();
        $this->phone = $this->faker->phoneNumber();
        $this->name = $this->faker->name();
        $this->plainTextPassword = $this->faker->password();
        $this->password = Hash::make($this->plainTextPassword);
        return $this;
    }

    public function getIdentity(): string
    {
        return $this->phone ?? $this->email;
    }
}
