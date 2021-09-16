<?php

namespace App\Contexts\Cards\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class CardSatisfied extends BaseIntegrationEvent
{
    protected ?string $instanceOf = 'Card';
}