<?php

namespace App\Contexts\Collaboration\Application\Commands\Relation;

use App\Contexts\Collaboration\Domain\Model\Relation\CollaboratorId;
use App\Contexts\Collaboration\Domain\Model\Workspace\WorkspaceId;

final class LeaveRelation implements LeaveRelationCommandInterface
{
    private function __construct(
        private string $collaboratorId,
        private string $workspaceId,
    ) {
    }

    public static function of(string $collaboratorId, string $workspaceId): self
    {
        return new self($collaboratorId, $workspaceId);
    }

    public function getCollaboratorId(): CollaboratorId
    {
        return CollaboratorId::of($this->collaboratorId);
    }

    public function getWorkspaceId(): WorkspaceId
    {
        return WorkspaceId::of($this->workspaceId);
    }
}
