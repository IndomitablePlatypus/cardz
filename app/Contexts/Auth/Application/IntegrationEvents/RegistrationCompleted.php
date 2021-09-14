<?php

namespace App\Contexts\Auth\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class RegistrationCompleted extends BaseIntegrationEvent
{
    protected ?string $instanceOf = 'User';
}
