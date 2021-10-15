<?php

namespace App\Contexts\Collaboration\Infrastructure\Persistence\Eloquent;

use App\Contexts\Collaboration\Domain\Model\Collaborator\CollaboratorId;
use App\Contexts\Collaboration\Domain\Model\Collaborator\Keeper;
use App\Contexts\Collaboration\Domain\Model\Workspace\WorkspaceId;
use App\Contexts\Collaboration\Infrastructure\Persistence\Contracts\KeeperRepositoryInterface;
use App\Models\Workspace as EloquentKeeper;

class KeeperRepository implements KeeperRepositoryInterface
{
    public function take(CollaboratorId $keeperId, WorkspaceId $workspaceId): ?Keeper
    {
        $keeper = EloquentKeeper::query()
            ->where('keeper_id', '=', (string) $keeperId)
            ->where('id', '=', (string) $workspaceId)
            ->first();
        return $keeper ? new Keeper($keeperId, $workspaceId) : null;
    }
}
