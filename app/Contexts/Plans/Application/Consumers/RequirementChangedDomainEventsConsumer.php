<?php

namespace App\Contexts\Plans\Application\Consumers;

use App\Contexts\Plans\Domain\Events\Requirement\RequirementChanged as DomainRequirementChanged;
use App\Contexts\Plans\Infrastructure\ReadStorage\Contracts\ReadRequirementStorageInterface;
use App\Contexts\Plans\Integration\Events\RequirementChanged;
use App\Shared\Contracts\Messaging\EventConsumerInterface;
use App\Shared\Contracts\Messaging\EventInterface;
use App\Shared\Contracts\Messaging\IntegrationEventBusInterface;

final class RequirementChangedDomainEventsConsumer implements EventConsumerInterface
{

    public function __construct(
        private IntegrationEventBusInterface $integrationEventBus,
        private ReadRequirementStorageInterface $readRequirementStorage,
    ) {
    }

    public function consumes(): array
    {
        return [
            DomainRequirementChanged::class,
        ];
    }

    public function handle(EventInterface $event): void
    {
        if (!$event instanceof DomainRequirementChanged) {
            return;
        }

        $requirement = $this->readRequirementStorage->take($event->with()?->requirementId);
        if ($requirement !== null) {
            $this->integrationEventBus->publish(RequirementChanged::of($requirement));
        }
    }

}
