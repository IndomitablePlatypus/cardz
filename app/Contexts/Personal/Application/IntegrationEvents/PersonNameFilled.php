<?php

namespace App\Contexts\Personal\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class PersonNameFilled extends BaseIntegrationEvent
{
    protected string $in = 'Personal';

    protected string $of = 'Person';
}
