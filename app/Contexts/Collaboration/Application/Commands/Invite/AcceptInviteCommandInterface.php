<?php

namespace App\Contexts\Collaboration\Application\Commands\Invite;

use App\Contexts\Collaboration\Domain\Model\Relation\CollaboratorId;
use App\Contexts\Collaboration\Domain\Model\Relation\RelationId;
use App\Contexts\Collaboration\Domain\Model\Workspace\WorkspaceId;

interface AcceptInviteCommandInterface extends InviteCommandInterface
{
    public function getRelationId(): RelationId;

    public function getCollaboratorId(): CollaboratorId;
}
