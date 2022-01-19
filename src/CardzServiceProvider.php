<?php

namespace Cardz;

use Cardz\Core\Cards\CardsProvider;
use Cardz\Core\Personal\PersonalProvider;
use Cardz\Core\Plans\PlansProvider;
use Cardz\Core\Workspaces\WorkspacesProvider;
use Cardz\Generic\Authorization\AuthorizationProvider;
use Cardz\Generic\Identity\IdentityProvider;
use Cardz\Support\Collaboration\CollaborationProvider;
use Cardz\Support\MobileAppGateway\MobileAppGatewayProvider;
use Codderz\Platypus\Contracts\Commands\CommandBusInterface;
use Codderz\Platypus\Contracts\Messaging\EventBusInterface;
use Codderz\Platypus\Contracts\Messaging\IntegrationEventBusInterface;
use Codderz\Platypus\Contracts\Queries\QueryBusInterface;
use Codderz\Platypus\Infrastructure\CommandHandling\QueuedSyncCommandBus;
use Codderz\Platypus\Infrastructure\Messaging\EventBus;
use Codderz\Platypus\Infrastructure\Messaging\IntegrationEventBus;
use Codderz\Platypus\Infrastructure\Messaging\LocalSyncMessageBroker;
use Codderz\Platypus\Infrastructure\Messaging\RabbitMQMessageBroker;
use Codderz\Platypus\Infrastructure\QueryHandling\SyncQueryBus;
use Illuminate\Support\ServiceProvider;

final class CardzServiceProvider extends ServiceProvider
{
    public static function providers(): array
    {
        return [
            self::class,
            IdentityProvider::class,
            AuthorizationProvider::class,

            MobileAppGatewayProvider::class,
            CollaborationProvider::class,

            CardsProvider::class,
            PlansProvider::class,
            PersonalProvider::class,
            WorkspacesProvider::class,
        ];
    }

    public function register()
    {
        $this->app->singleton(CommandBusInterface::class, QueuedSyncCommandBus::class);
        $this->app->singleton(QueryBusInterface::class, SyncQueryBus::class);

        $this->app->singleton(EventBusInterface::class, fn() => new EventBus($this->app->make(LocalSyncMessageBroker::class)));
        $this->app->singleton(IntegrationEventBusInterface::class, fn() => new IntegrationEventBus($this->app->make(RabbitMQMessageBroker::class)));
    }

}
