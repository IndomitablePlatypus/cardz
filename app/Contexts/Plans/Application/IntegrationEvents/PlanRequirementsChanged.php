<?php

namespace App\Contexts\Plans\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
class PlanRequirementsChanged extends BaseIntegrationEvent
{
    protected ?string $instanceOf = 'Plan';
}
