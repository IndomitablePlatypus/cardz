<?php

namespace App\Contexts\Workspaces\Infrastructure\Persistence\Contracts;

use App\Contexts\Workspaces\Domain\Model\Workspace\Workspace;
use App\Contexts\Workspaces\Domain\Model\Workspace\WorkspaceId;

interface WorkspaceRepositoryInterface
{
    public function persist(?Workspace $workspace): void;

    public function take(WorkspaceId $workspaceId): ?Workspace;
}
