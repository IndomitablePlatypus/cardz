<?php

namespace App\Contexts\Collaboration\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class RelationLeft extends BaseIntegrationEvent
{
    protected string $in = 'Collaboration';

    protected string $of = 'Relation';
}
