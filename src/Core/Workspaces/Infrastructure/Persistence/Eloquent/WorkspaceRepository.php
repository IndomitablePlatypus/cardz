<?php

namespace Cardz\Core\Workspaces\Infrastructure\Persistence\Eloquent;

use Cardz\Core\Workspaces\Domain\Exceptions\WorkspaceNotFoundExceptionInterface;
use Cardz\Core\Workspaces\Domain\Model\Workspace\Workspace;
use Cardz\Core\Workspaces\Domain\Model\Workspace\WorkspaceId;
use Cardz\Core\Workspaces\Domain\Persistence\Contracts\WorkspaceRepositoryInterface;
use Cardz\Core\Workspaces\Infrastructure\Exceptions\WorkspaceNotFoundException;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Persistence\EventStore\Eloquent\EloquentEventRepositoryTrait;

class WorkspaceRepository implements WorkspaceRepositoryInterface
{
    use EloquentEventRepositoryTrait;

    /**
     * @throws WorkspaceNotFoundExceptionInterface
     */
    public function restore(WorkspaceId $workspaceId): Workspace
    {
        return Workspace::fromEvents(...$this->getRestoredEvents($workspaceId))
            ?? throw new WorkspaceNotFoundException("Workspace $workspaceId cannot be restored");
    }

    protected function getAggregateRootName(): string
    {
        return Workspace::class;
    }

    protected function assertAggregateRootCanBeRestored(GenericIdInterface $id, bool $eventsExist): void
    {
        if (!$eventsExist) {
            throw new WorkspaceNotFoundException("Workspace $id not found");
        }
    }
}
