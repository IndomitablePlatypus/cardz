<?php

namespace Cardz\Core\Workspaces\Domain\Model\Workspace;

use Carbon\Carbon;
use Cardz\Core\Workspaces\Domain\Events\Keeper\KeeperRegistered;
use Codderz\Platypus\Contracts\Domain\EventDrivenAggregateRootInterface;
use Codderz\Platypus\Infrastructure\Support\Domain\EventDrivenAggregateRootTrait;
use JetBrains\PhpStorm\Pure;

final class Keeper implements EventDrivenAggregateRootInterface
{
    use EventDrivenAggregateRootTrait;

    #[Pure]
    public function __construct(
        public KeeperId $keeperId,
    ) {
    }

    public static function register(KeeperId $keeperId): self
    {
        return (new self($keeperId))->recordThat(KeeperRegistered::of());
    }

    public function id(): KeeperId
    {
        return $this->keeperId;
    }

    public function keepWorkspace(WorkspaceId $workspaceId, Profile $profile): Workspace
    {
        return (new Workspace($workspaceId))->add($this->keeperId, $profile, Carbon::now());
    }
}
