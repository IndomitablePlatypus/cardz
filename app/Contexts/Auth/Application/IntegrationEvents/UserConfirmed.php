<?php

namespace App\Contexts\Auth\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class UserConfirmed extends BaseIntegrationEvent
{
    protected ?string $instanceOf = 'User';
}