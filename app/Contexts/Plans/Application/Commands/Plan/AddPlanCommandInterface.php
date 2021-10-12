<?php

namespace App\Contexts\Plans\Application\Commands\Plan;

use App\Contexts\Plans\Domain\Model\Plan\Description;
use App\Contexts\Plans\Domain\Model\Plan\PlanId;
use App\Contexts\Plans\Domain\Model\Plan\WorkspaceId;

interface AddPlanCommandInterface extends PlanCommandInterface
{
    public function getWorkspaceId(): WorkspaceId;

    public function getDescription(): Description;
}
