<?php

namespace Cardz\Support\MobileAppGateway;

use Cardz\Support\MobileAppGateway\Infrastructure\ACL\Cards\MonolithCardsAdapter;
use Cardz\Support\MobileAppGateway\Infrastructure\ACL\Collaboration\MonolithCollaborationAdapter;
use Cardz\Support\MobileAppGateway\Infrastructure\ACL\Identity\MonolithIdentityAdapter;
use Cardz\Support\MobileAppGateway\Infrastructure\ACL\Plans\MonolithPlansAdapter;
use Cardz\Support\MobileAppGateway\Infrastructure\ACL\Workspaces\MonolithWorkspacesAdapter;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Customer\Contracts\CustomerProfileReadStorageInterface;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Customer\Contracts\CustomerWorkspaceReadStorageInterface;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Customer\Contracts\IssuedCardReadStorageInterface;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Customer\Eloquent\CustomerProfileReadStorage;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Customer\Eloquent\CustomerWorkspaceReadStorage;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Customer\Eloquent\IssuedCardReadStorage;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Workspace\Contracts\BusinessCardReadStorageInterface;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Workspace\Contracts\BusinessPlanReadStorageInterface;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Workspace\Contracts\BusinessWorkspaceReadStorageInterface;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Workspace\Eloquent\BusinessCardReadStorage;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Workspace\Eloquent\BusinessPlanReadStorage;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Workspace\Eloquent\BusinessWorkspaceReadStorage;
use Cardz\Support\MobileAppGateway\Integration\Contracts\CardsContextInterface;
use Cardz\Support\MobileAppGateway\Integration\Contracts\CollaborationContextInterface;
use Cardz\Support\MobileAppGateway\Integration\Contracts\IdentityContextInterface;
use Cardz\Support\MobileAppGateway\Integration\Contracts\PlansContextInterface;
use Cardz\Support\MobileAppGateway\Integration\Contracts\WorkspacesContextInterface;
use Illuminate\Support\ServiceProvider;

class MobileAppGatewayProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IssuedCardReadStorageInterface::class, IssuedCardReadStorage::class);
        $this->app->singleton(CustomerProfileReadStorageInterface::class, CustomerProfileReadStorage::class);
        $this->app->singleton(CustomerWorkspaceReadStorageInterface::class, CustomerWorkspaceReadStorage::class);
        $this->app->singleton(BusinessCardReadStorageInterface::class, BusinessCardReadStorage::class);
        $this->app->singleton(BusinessPlanReadStorageInterface::class, BusinessPlanReadStorage::class);
        $this->app->singleton(BusinessWorkspaceReadStorageInterface::class, BusinessWorkspaceReadStorage::class);

        $this->app->singleton(IdentityContextInterface::class, MonolithIdentityAdapter::class);
        $this->app->singleton(CardsContextInterface::class, MonolithCardsAdapter::class);
        $this->app->singleton(CollaborationContextInterface::class, MonolithCollaborationAdapter::class);
        $this->app->singleton(PlansContextInterface::class, MonolithPlansAdapter::class);
        $this->app->singleton(WorkspacesContextInterface::class, MonolithWorkspacesAdapter::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/Config/Routes/api.php');
    }
}
