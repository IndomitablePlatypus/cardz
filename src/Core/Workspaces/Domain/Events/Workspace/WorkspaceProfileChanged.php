<?php

namespace Cardz\Core\Workspaces\Domain\Events\Workspace;

use Cardz\Core\Workspaces\Domain\Model\Workspace\Profile;
use JetBrains\PhpStorm\Pure;

final class WorkspaceProfileChanged extends BaseWorkspaceDomainEvent
{
    private function __construct(
        public Profile $profile,
    ) {
    }

    #[Pure]
    public static function of(Profile $profile): self
    {
        return new self($profile);
    }

    #[Pure]
    public static function from(array $data): self
    {
        return new self(Profile::of(...$data['profile']));
    }

}
