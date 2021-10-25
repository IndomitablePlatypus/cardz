<?php

namespace App\Contexts\Auth\Infrastructure\Persistence\Eloquent;

use App\Contexts\Auth\Domain\Model\User\User;
use App\Contexts\Auth\Domain\Model\User\UserIdentity;
use App\Contexts\Auth\Domain\Persistence\Contracts\UserRepositoryInterface;
use App\Contexts\Auth\Infrastructure\Exceptions\UserNotFoundException;
use App\Models\User as EloquentUser;
use App\Shared\Infrastructure\Support\PropertiesExtractorTrait;

class UserRepository implements UserRepositoryInterface
{
    use PropertiesExtractorTrait;

    public function persist(User $user): void
    {
        EloquentUser::query()->updateOrCreate(
            ['id' => (string) $user->userId],
            $this->userAsData($user)
        );
    }

    public function isExistingIdentity(UserIdentity $userIdentity): bool
    {
        $query = EloquentUser::query();
        if ($userIdentity->getEmail() !== null) {
            $query->where('email', '=', $userIdentity->getEmail());
        }
        if ($userIdentity->getPhone() !== null) {
            $query->where('phone', '=', $userIdentity->getPhone());
        }

        $eloquentUser = $query->first();
        return $eloquentUser !== null;
    }

    public function takeByIdentity(string $identity): User
    {
        /** @var EloquentUser $eloquentUser */
        $eloquentUser = EloquentUser::query()
            ->where('email', '=', $identity)
            ->orWhere('phone', '=', $identity)
            ->first();
        if (!$eloquentUser) {
            throw new UserNotFoundException("User $identity not found");
        }
        return $this->userFromData($eloquentUser);
    }

    private function userAsData(User $user): array
    {
        $properties = $this->extractProperties($user, 'registrationInitiated', 'emailVerified', 'password', 'rememberToken');
        $data = [
            'id' => $properties['userId'],
            'email' => $properties['email'],
            'phone' => $properties['phone'],
            'name' => $properties['name'],
            'password' => (string) $properties['password'],
            'remember_token' => $properties['rememberToken'],
            'registration_initiated_at' => $properties['registrationInitiated'],
            'email_verified_at' => $properties['emailVerified'],
        ];

        return $data;
    }

    private function userFromData(EloquentUser $eloquentUser): User
    {
        $user = User::restore(
            $eloquentUser->id,
            $eloquentUser->email,
            $eloquentUser->phone,
            $eloquentUser->name,
            $eloquentUser->password,
            $eloquentUser->getRememberToken(),
            $eloquentUser->registration_initiated_at,
            $eloquentUser->email_verified_at,
        );
        return $user;
    }
}
