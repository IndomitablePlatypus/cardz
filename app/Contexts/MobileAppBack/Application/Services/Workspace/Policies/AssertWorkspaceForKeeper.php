<?php

namespace App\Contexts\MobileAppBack\Application\Services\Workspace\Policies;

use App\Contexts\MobileAppBack\Domain\Model\Collaboration\KeeperId;
use App\Contexts\MobileAppBack\Domain\Model\Workspace\WorkspaceId;
use App\Models\Workspace as EloquentWorkspace;
use App\Shared\Contracts\PolicyAssertionInterface;
use App\Shared\Contracts\PolicyViolationInterface;
use App\Shared\Infrastructure\Policy\PolicyViolation;
use JetBrains\PhpStorm\Pure;

final class AssertWorkspaceForKeeper implements PolicyAssertionInterface
{
    private function __construct(
        private WorkspaceId $workspaceId,
        private KeeperId $keeperId,
    ) {
    }

    #[Pure]
    public static function of(WorkspaceId $workspaceId, KeeperId $keeperId): self
    {
        return new self($workspaceId, $keeperId);
    }

    public function assert(): bool
    {
        $workspace = EloquentWorkspace::query()
            ->where('id', '=', (string) $this->workspaceId)
            ->where('keeper_id', '=', (string) $this->keeperId)
            ->first();
        return $workspace !== null;
    }

    public function violation(): PolicyViolationInterface
    {
        return PolicyViolation::of("Workspace {$this->workspaceId} is not for keeper {$this->keeperId}");
    }

}
