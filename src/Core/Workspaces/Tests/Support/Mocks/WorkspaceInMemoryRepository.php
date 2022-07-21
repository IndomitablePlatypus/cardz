<?php

namespace Cardz\Core\Workspaces\Tests\Support\Mocks;

use Cardz\Core\Workspaces\Domain\Model\Workspace\Workspace;
use Cardz\Core\Workspaces\Domain\Model\Workspace\WorkspaceId;
use Cardz\Core\Workspaces\Domain\Persistence\Contracts\WorkspaceRepositoryInterface;

class WorkspaceInMemoryRepository implements WorkspaceRepositoryInterface
{
    protected static array $events = [];

    public function store(Workspace $workspace): array
    {
        $id = (string) $workspace->workspaceId;
        $events = $workspace->releaseEvents();
        static::$events[$id] ??= [];
        static::$events[$id] = [...static::$events[$id], ...$events];
        return $events;
    }

    public function restore(WorkspaceId $workspaceId): Workspace
    {
        $events = collect(static::$events[(string) $workspaceId] ??= [])->sortByDesc(function ($event, $key) {
            return $event->at()->timestamp;
        });
        return Workspace::draft($workspaceId)->apply(...$events->all());
    }
}
