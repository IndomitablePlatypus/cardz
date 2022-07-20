<?php

namespace Cardz\Core\Cards;

use Cardz\Core\Cards\Application\Consumers\CardChangedDomainEventConsumer;
use Cardz\Core\Cards\Application\Projectors\CardChangedProjector;
use Cardz\Core\Cards\Application\Services\CardAppService;
use Cardz\Core\Cards\Domain\Persistence\Contracts\CardRepositoryInterface;
use Cardz\Core\Cards\Domain\Persistence\Contracts\PlanRepositoryInterface;
use Cardz\Core\Cards\Infrastructure\Messaging\DomainEventBus;
use Cardz\Core\Cards\Infrastructure\Messaging\DomainEventBusInterface;
use Cardz\Core\Cards\Infrastructure\Persistence\Eloquent\CardRepository;
use Cardz\Core\Cards\Infrastructure\Persistence\Eloquent\PlanRepository;
use Cardz\Core\Cards\Infrastructure\ReadStorage\Contracts\CardReadStorageInterface;
use Cardz\Core\Cards\Infrastructure\ReadStorage\Contracts\IssuedCardReadStorageInterface;
use Cardz\Core\Cards\Infrastructure\ReadStorage\Eloquent\CardReadStorage;
use Cardz\Core\Cards\Infrastructure\ReadStorage\Eloquent\IssuedCardReadStorage;
use Cardz\Core\Cards\Integration\Consumers\PlansRequirementDescriptionChangedConsumer;
use Cardz\Core\Cards\Integration\Consumers\PlansRequirementsChangedConsumer;
use Codderz\Platypus\Contracts\Commands\CommandBusInterface;
use Codderz\Platypus\Contracts\Messaging\IntegrationEventBusInterface;
use Codderz\Platypus\Infrastructure\CommandHandling\LaravelHandlerGenerator;
use Illuminate\Support\ServiceProvider;

class CardsProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CardRepositoryInterface::class, CardRepository::class);
        $this->app->singleton(IssuedCardReadStorageInterface::class, IssuedCardReadStorage::class);
        $this->app->singleton(CardReadStorageInterface::class, CardReadStorage::class);
        $this->app->singleton(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->singleton(DomainEventBusInterface::class, DomainEventBus::class);
    }

    public function boot(
        CommandBusInterface $commandBus,
        DomainEventBusInterface $domainEventBus,
        IntegrationEventBusInterface $integrationEventBus,
    ) {
        $commandBus->registerProvider(LaravelHandlerGenerator::of(CardAppService::class));

        $integrationEventBus->subscribe($this->app->make(PlansRequirementsChangedConsumer::class));
        $integrationEventBus->subscribe($this->app->make(PlansRequirementDescriptionChangedConsumer::class));

        $domainEventBus->subscribe($this->app->make(CardChangedDomainEventConsumer::class));
        $domainEventBus->subscribe($this->app->make(CardChangedProjector::class));
    }
}
