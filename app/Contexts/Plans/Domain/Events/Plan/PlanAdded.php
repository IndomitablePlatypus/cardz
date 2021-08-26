<?php

namespace App\Contexts\Plans\Domain\Events\Plan;

use App\Contexts\Plans\Domain\Model\Plan\PlanId;

class PlanAdded extends BasePlanDomainEvent
{
    public static function with(PlanId $planId): static
    {
        return new static($planId);
    }
}
