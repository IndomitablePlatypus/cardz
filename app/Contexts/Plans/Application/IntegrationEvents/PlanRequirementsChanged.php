<?php

namespace App\Contexts\Plans\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class PlanRequirementsChanged extends BaseIntegrationEvent
{
    protected string $in = 'Plans';

    protected string $of = 'Plan';
}
