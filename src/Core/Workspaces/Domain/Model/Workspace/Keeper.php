<?php

namespace Cardz\Core\Workspaces\Domain\Model\Workspace;

use Carbon\Carbon;
use Cardz\Core\Workspaces\Domain\Events\Keeper\KeeperRegistered;
use Codderz\Platypus\Contracts\Domain\EventDrivenAggregateRootInterface;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Support\Domain\EventDrivenAggregateRootTrait;
use JetBrains\PhpStorm\Pure;

final class Keeper implements EventDrivenAggregateRootInterface
{
    use EventDrivenAggregateRootTrait;

    protected static function idFromEventStream(GenericIdInterface $id): KeeperId
    {
        return KeeperId::of($id);
    }

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
        return Workspace::draft($workspaceId)->add($this->keeperId, $profile, Carbon::now());
    }
}
