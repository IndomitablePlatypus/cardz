<?php

namespace App\Contexts\Collaboration\Tests\Support\Builders;

use App\Contexts\Collaboration\Domain\Model\Invite\Invite;
use App\Contexts\Collaboration\Domain\Model\Invite\InviteId;
use App\Contexts\Collaboration\Domain\Model\Invite\InviterId;
use App\Contexts\Collaboration\Domain\Model\Workspace\WorkspaceId;
use App\Shared\Infrastructure\Tests\BaseBuilder;
use Carbon\Carbon;

final class InviteBuilder extends BaseBuilder
{
    private string $inviteId;

    private string $inviterId;

    private string $workspaceId;

    private Carbon $proposed;

    public function build(): Invite
    {
        return Invite::restore(
            $this->inviteId,
            $this->inviterId,
            $this->workspaceId,
            $this->proposed,
        );
    }

    public function generate(): static
    {
        $this->inviteId = InviteId::makeValue();
        $this->inviterId = InviterId::makeValue();
        $this->workspaceId = WorkspaceId::makeValue();
        $this->proposed = Carbon::now();
        return $this;
    }
}
