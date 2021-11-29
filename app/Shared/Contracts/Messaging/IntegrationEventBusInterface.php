<?php

namespace App\Shared\Contracts\Messaging;

interface IntegrationEventBusInterface
{
    public function subscribe(IntegrationEventConsumerInterface ...$integrationIntegrationEventConsumers): void;

    public function publish(EventInterface ...$events): void;

    public function hasRecordedEvent($eventIdentifier): bool;
}
