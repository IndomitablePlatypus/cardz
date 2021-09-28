<?php

namespace App\Contexts\Collaboration\Application\IntegrationEvents;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class InviteAccepted extends BaseIntegrationEvent
{
    protected string $in = 'Collaboration';

    protected string $of = 'Invite';
}
