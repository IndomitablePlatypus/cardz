<?php

namespace App\Contexts\Cards;

use App\Contexts\Cards\Application\Controllers\Consumers\CardIssuedConsumer;
use App\Contexts\Cards\Application\Controllers\Consumers\CardRevokedConsumer;
use App\Contexts\Cards\Application\Controllers\Consumers\SatisfactionCheckRequiredConsumer;
use App\Contexts\Cards\Infrastructure\Persistence\BlockedCardRepository;
use App\Contexts\Cards\Infrastructure\Persistence\CardRepository;
use App\Contexts\Cards\Infrastructure\Persistence\Contracts\BlockedCardRepositoryInterface;
use App\Contexts\Cards\Infrastructure\Persistence\Contracts\CardRepositoryInterface;
use App\Contexts\Cards\Infrastructure\ReadStorage\Contracts\IssuedCardReadStorageInterface;
use App\Contexts\Cards\Infrastructure\ReadStorage\Contracts\ReadPlanStorageInterface;
use App\Contexts\Cards\Infrastructure\ReadStorage\Eloquent\IssuedCardReadStorage;
use App\Contexts\Cards\Infrastructure\ReadStorage\Eloquent\ReadPlanStorage;
use App\Contexts\Cards\Integration\Consumers\PlansRequirementDescriptionChangedConsumer;
use App\Contexts\Cards\Integration\Consumers\PlansRequirementsChangedConsumer;
use App\Shared\Contracts\Messaging\IntegrationEventBusInterface;
use App\Shared\Contracts\ReportingBusInterface;
use Illuminate\Support\ServiceProvider;

class CardsProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CardRepositoryInterface::class, CardRepository::class);
        $this->app->singleton(BlockedCardRepositoryInterface::class, BlockedCardRepository::class);
        $this->app->singleton(IssuedCardReadStorageInterface::class, IssuedCardReadStorage::class);
        $this->app->singleton(ReadPlanStorageInterface::class, ReadPlanStorage::class);
    }

    public function boot(
        ReportingBusInterface $reportingBus,
        IntegrationEventBusInterface $integrationEventBus,
    ) {
        $reportingBus->subscribe($this->app->make(CardIssuedConsumer::class));
        $reportingBus->subscribe($this->app->make(CardRevokedConsumer::class));
        $reportingBus->subscribe($this->app->make(SatisfactionCheckRequiredConsumer::class));

        $integrationEventBus->subscribe($this->app->make(PlansRequirementsChangedConsumer::class));
        $integrationEventBus->subscribe($this->app->make(PlansRequirementDescriptionChangedConsumer::class));
    }
}
