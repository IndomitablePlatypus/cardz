<?php

namespace App\Contexts\Auth\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class RegistrationInitiated extends BaseIntegrationEvent
{
    protected ?string $instanceOf = 'User';
}