<?php

namespace App\Contexts\MobileAppBack\Infrastructure\ACL\Identity;

use App\Contexts\Identity\Application\Commands\RegisterUser;
use App\Contexts\Identity\Application\Queries\GetToken;
use App\Contexts\MobileAppBack\Integration\Contracts\IdentityContextInterface;
use App\Shared\Contracts\Commands\CommandBusInterface;
use App\Shared\Contracts\Queries\QueryBusInterface;

class MonolithIdentityAdapter implements IdentityContextInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function registerUser(?string $email, ?string $phone, string $name, string $password): string
    {
        $command = RegisterUser::of($name, $password, $email, $phone);
        $this->commandBus->dispatch($command);
        return $command->getUserId();
    }

    public function getToken(string $identity, string $password, string $deviceName): string
    {
        $query = GetToken::of($identity, $password, $deviceName);
        return $this->queryBus->execute($query);
    }

}