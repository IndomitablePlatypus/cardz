<?php

namespace App\Contexts\Collaboration\Application\Services;

use App\Contexts\Collaboration\Domain\Model\Collaborator\CollaboratorId;
use App\Contexts\Collaboration\Domain\Model\Invite\InviteId;
use App\Contexts\Collaboration\Domain\Model\Relation\RelationId;
use App\Contexts\Collaboration\Domain\Model\Workspace\WorkspaceId;
use App\Contexts\Collaboration\Domain\Persistence\Contracts\InviteRepositoryInterface;
use App\Contexts\Collaboration\Domain\Persistence\Contracts\KeeperRepositoryInterface;
use App\Contexts\Collaboration\Domain\Persistence\Contracts\RelationRepositoryInterface;
use App\Shared\Infrastructure\Support\ReportingServiceTrait;

class KeeperAppService
{
    use ReportingServiceTrait;

    public function __construct(
        private KeeperRepositoryInterface $keeperRepository,
        private RelationRepositoryInterface $relationRepository,
        private InviteRepositoryInterface $inviteRepository,
    ) {
    }

    public function bindWorkspace(string $keeperId, string $workspaceId): RelationId
    {
        $keeper = $this->keeperRepository->take(CollaboratorId::of($keeperId), WorkspaceId::of($workspaceId));
        $relation = $keeper->keepWorkspace();
        $this->relationRepository->persist($relation);
        return $relation->relationId;
    }

    public function invite(string $keeperId, string $memberId, string $workspaceId): InviteId
    {
        $keeper = $this->keeperRepository->take(CollaboratorId::of($keeperId), WorkspaceId::of($workspaceId));
        $invite = $keeper->invite(CollaboratorId::of($memberId));
        $this->inviteRepository->persist($invite);
        return $invite->inviteId;
    }
}
