<?php

namespace Cardz\Core\Workspaces\Domain\Events\Workspace;

use Carbon\Carbon;
use Cardz\Core\Workspaces\Domain\Model\Workspace\KeeperId;
use Cardz\Core\Workspaces\Domain\Model\Workspace\Profile;

final class WorkspaceAdded extends BaseWorkspaceDomainEvent
{
    private function __construct(
        public KeeperId $keeperId,
        public Profile $profile,
        public Carbon $added,
    ) {
    }

    public static function of(KeeperId $keeperId, Profile $profile, Carbon $added): self
    {
        return new self($keeperId, $profile, $added);
    }

    public static function from(array $data): self
    {
        return new self(
            KeeperId::of($data['keeperId']),
            Profile::ofData($data['profile']),
            new Carbon($data['added']),
        );
    }
}
