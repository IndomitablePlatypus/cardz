<?php

namespace App\Contexts\Collaboration\Infrastructure\ReadStorage;

use App\Contexts\Collaboration\Application\Contracts\AddedWorkspaceReadStorageInterface;
use App\Contexts\Collaboration\Domain\Model\Collaborator\CollaboratorId;
use App\Contexts\Collaboration\Domain\Model\Workspace\WorkspaceId;
use App\Contexts\Collaboration\Domain\ReadModel\AddedWorkspace;
use App\Models\Workspace as EloquentWorkspace;

class AddedWorkspaceReadStorage implements AddedWorkspaceReadStorageInterface
{
    public function find(string $workspaceId): ?AddedWorkspace
    {
        $eloquentWorkspace = EloquentWorkspace::query()->find($workspaceId);
        if ($eloquentWorkspace === null) {
            return null;
        }
        return new AddedWorkspace(WorkspaceId::of($eloquentWorkspace->id), CollaboratorId::of($eloquentWorkspace->keeper_id));
    }

}