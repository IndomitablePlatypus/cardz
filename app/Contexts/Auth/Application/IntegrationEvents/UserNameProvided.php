<?php

namespace App\Contexts\Auth\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class UserNameProvided extends BaseIntegrationEvent
{
    protected string $in = 'Auth';

    protected string $of = 'User';
}
