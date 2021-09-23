<?php

namespace App\Contexts\Cards\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class RequirementsAccepted extends BaseIntegrationEvent
{
    protected string $in = 'Cards';

    protected string $of = 'Card';
}
