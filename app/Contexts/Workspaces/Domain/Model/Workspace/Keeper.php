<?php

namespace App\Contexts\Workspaces\Domain\Model\Workspace;

use App\Shared\Contracts\Domain\AggregateRootInterface;
use App\Shared\Infrastructure\Support\Domain\AggregateRootTrait;
use JetBrains\PhpStorm\Pure;

final class Keeper implements AggregateRootInterface
{
    use AggregateRootTrait;

    #[Pure]
    private function __construct(
        public KeeperId $keeperId,
    ) {
    }

    #[Pure]
    public static function restore(KeeperId $keeperId): self
    {
        return new self($keeperId);
    }

    public function keepWorkspace(WorkspaceId $workspaceId, Profile $profile): Workspace
    {
        return Workspace::add(
            $workspaceId,
            $this->keeperId,
            $profile,
        );
    }
}
