<?php

namespace App\Contexts\Plans\Application\Commands\Requirement;

use App\Contexts\Plans\Domain\Model\Requirement\RequirementId;
use App\Shared\Contracts\Commands\CommandInterface;

interface RemoveRequirementCommandInterface extends CommandInterface
{
    public function getRequirementId(): RequirementId;
}
