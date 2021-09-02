<?php

namespace App\Contexts\Plans\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
class AchievementAdded extends BaseIntegrationEvent
{
    protected ?string $instanceOf = 'Achievement';
}
