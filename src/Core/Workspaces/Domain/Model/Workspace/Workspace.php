<?php

namespace Cardz\Core\Workspaces\Domain\Model\Workspace;

use Carbon\Carbon;
use Cardz\Core\Workspaces\Domain\Events\Workspace\WorkspaceAdded;
use Cardz\Core\Workspaces\Domain\Events\Workspace\WorkspaceProfileChanged;
use Codderz\Platypus\Contracts\Domain\EventDrivenAggregateRootInterface;
use Codderz\Platypus\Infrastructure\Support\Domain\EventDrivenAggregateRootTrait;

final class Workspace implements EventDrivenAggregateRootInterface
{
    use EventDrivenAggregateRootTrait;

    public KeeperId $keeperId;

    public Profile $profile;

    public ?Carbon $added = null;

    public function __construct(
        public WorkspaceId $workspaceId,
    ) {
    }

    public static function restore(string $workspaceId, string $keeperId, ?Carbon $added, array $profile): self
    {
        $workspace = new self(WorkspaceId::of($workspaceId));
        $workspace->added = $added;
        $workspace->keeperId = KeeperId::of($keeperId);
        $workspace->profile = Profile::of(...$profile);
        $workspace->added = new Carbon($added);
        return $workspace;
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
