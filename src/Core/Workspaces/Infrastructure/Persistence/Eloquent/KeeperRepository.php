<?php

namespace Cardz\Core\Workspaces\Infrastructure\Persistence\Eloquent;

use Cardz\Core\Workspaces\Domain\Exceptions\KeeperNotFoundExceptionInterface;
use Cardz\Core\Workspaces\Domain\Model\Workspace\Keeper;
use Cardz\Core\Workspaces\Domain\Model\Workspace\KeeperId;
use Cardz\Core\Workspaces\Domain\Persistence\Contracts\KeeperRepositoryInterface;
use Cardz\Core\Workspaces\Infrastructure\Exceptions\KeeperNotFoundException;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Persistence\EventStore\Eloquent\EloquentEventRepositoryTrait;

class KeeperRepository implements KeeperRepositoryInterface
{
    use EloquentEventRepositoryTrait;

    /**
     * @throws KeeperNotFoundExceptionInterface
     */
    public function restore(KeeperId $keeperId): Keeper
    {
        return (new Keeper($keeperId))->apply(...$this->getRestoredEvents($keeperId));
    }

    protected function getAggregateRootName(): string
    {
        return Keeper::class;
    }

    protected function assertAggregateRootCanBeRestored(GenericIdInterface $id, bool $eventsExist): void
    {
        if (!$eventsExist) {
            throw new KeeperNotFoundException("Workspace $id not found");
        }
    }
}
