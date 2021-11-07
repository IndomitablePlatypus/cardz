<?php

namespace App\Contexts\MobileAppBack;

use App\Contexts\MobileAppBack\Application\Services\AuthorizationService;
use App\Contexts\MobileAppBack\Application\Services\AuthorizationServiceInterface;
use App\Contexts\MobileAppBack\Infrastructure\ACL\Auth\MonolithAuthAdapter;
use App\Contexts\MobileAppBack\Infrastructure\ACL\Workspaces\MonolithWorkspacesAdapter;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\Customer\Contracts\CustomerWorkspaceReadStorageInterface;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\Customer\Eloquent\CustomerWorkspaceReadStorage;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\Shared\Contracts\IssuedCardReadStorageInterface;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\Shared\Eloquent\IssuedCardReadStorage;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\Workspace\Contracts\BusinessWorkspaceReadStorageInterface;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\Workspace\Contracts\WorkspacePlanReadStorageInterface;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\Workspace\Eloquent\BusinessWorkspaceReadStorage;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\Workspace\Eloquent\WorkspacePlanReadStorage;
use App\Contexts\MobileAppBack\Integration\Contracts\AuthContextInterface;
use App\Contexts\MobileAppBack\Integration\Contracts\WorkspacesContextInterface;
use Illuminate\Support\ServiceProvider;

class MobileAppBackProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IssuedCardReadStorageInterface::class, IssuedCardReadStorage::class);
        $this->app->singleton(CustomerWorkspaceReadStorageInterface::class, CustomerWorkspaceReadStorage::class);
        $this->app->singleton(BusinessWorkspaceReadStorageInterface::class, BusinessWorkspaceReadStorage::class);
        $this->app->singleton(WorkspacePlanReadStorageInterface::class, WorkspacePlanReadStorage::class);

        $this->app->singleton(AuthorizationServiceInterface::class, AuthorizationService::class);


        $this->app->singleton(AuthContextInterface::class, MonolithAuthAdapter::class);
        $this->app->singleton(WorkspacesContextInterface::class, MonolithWorkspacesAdapter::class);
    }

    public function boot()
    {
    }
}
