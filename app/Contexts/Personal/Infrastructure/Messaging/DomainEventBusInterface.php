<?php

namespace App\Contexts\Personal\Infrastructure\Messaging;

use App\Shared\Contracts\Messaging\EventConsumerInterface;
use App\Shared\Contracts\Messaging\EventInterface;

interface DomainEventBusInterface
{
    public function subscribe(EventConsumerInterface $consumer): void;

    public function publish(EventInterface ...$domainEvents): void;
}
