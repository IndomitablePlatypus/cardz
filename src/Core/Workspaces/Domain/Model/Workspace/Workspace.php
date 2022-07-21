<?php

namespace Cardz\Core\Workspaces\Domain\Model\Workspace;

use Carbon\Carbon;
use Cardz\Core\Workspaces\Domain\Events\Workspace\WorkspaceAdded;
use Cardz\Core\Workspaces\Domain\Events\Workspace\WorkspaceProfileChanged;
use Codderz\Platypus\Contracts\Domain\EventDrivenAggregateRootInterface;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Support\Domain\EventDrivenAggregateRootTrait;

final class Workspace implements EventDrivenAggregateRootInterface
{
    use EventDrivenAggregateRootTrait;

    public KeeperId $keeperId;

    public Profile $profile;

    public ?Carbon $added = null;

    protected static function idFromEventStream(GenericIdInterface $id): WorkspaceId
    {
        return WorkspaceId::of($id);
    }

    private function __construct(public WorkspaceId $workspaceId)
    {
    }

    public static function draft(WorkspaceId $workspaceId): self
    {
        return new self($workspaceId);
    }

    public function add(KeeperId $keeperId, Profile $profile, Carbon $added): self
    {
        return $this->recordThat(WorkspaceAdded::of($keeperId, $profile, $added));
    }

    public function id(): WorkspaceId
    {
        return $this->workspaceId;
    }

    public function changeProfile(Profile $profile): self
    {
        return $this->recordThat(WorkspaceProfileChanged::of($profile));
    }

    public function isAdded(): bool
    {
        return $this->added !== null;
    }
}
