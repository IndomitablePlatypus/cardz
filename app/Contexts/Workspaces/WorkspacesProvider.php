<?php

namespace App\Contexts\Workspaces;

use App\Contexts\Workspaces\Application\Services\WorkspaceAppService;
use App\Contexts\Workspaces\Infrastructure\Messaging\DomainEventBus;
use App\Contexts\Workspaces\Infrastructure\Messaging\DomainEventBusInterface;
use App\Contexts\Workspaces\Infrastructure\Persistence\Contracts\KeeperRepositoryInterface;
use App\Contexts\Workspaces\Infrastructure\Persistence\Contracts\WorkspaceRepositoryInterface;
use App\Contexts\Workspaces\Infrastructure\Persistence\Eloquent\KeeperRepository;
use App\Contexts\Workspaces\Infrastructure\Persistence\Eloquent\WorkspaceRepository;
use Illuminate\Support\ServiceProvider;

class WorkspacesProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(KeeperRepositoryInterface::class, KeeperRepository::class);
        $this->app->singleton(WorkspaceRepositoryInterface::class, WorkspaceRepository::class);
        $this->app->singleton(DomainEventBusInterface::class, DomainEventBus::class);
    }

    public function boot(
        WorkspaceAppService $workspaceAppService,
    ) {
        $workspaceAppService->registerHandlers();
    }
}
