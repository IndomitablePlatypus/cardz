<?php

namespace App\Contexts\Plans\Infrastructure\Persistence;

use App\Contexts\Plans\Application\Contracts\ReadPlanRepositoryInterface;
use App\Contexts\Plans\Domain\ReadModel\ReadPlan;
use App\Models\Plan as EloquentPlan;

class ReadPlanRepository implements ReadPlanRepositoryInterface
{
    public function take(string $planId): ?ReadPlan
    {
        /** @var EloquentPlan $eloquentPlan */
        $eloquentPlan = EloquentPlan::query()->where([
            'id' => $planId,
        ])?->first();
        if ($eloquentPlan === null) {
            return null;
        }
        return $this->readPlanFromData($eloquentPlan);
    }

    private function readPlanFromData(EloquentPlan $eloquentPlan): ReadPlan
    {
        $requirements = is_string($eloquentPlan->requirements) ? json_try_decode($eloquentPlan->requirements) : $eloquentPlan->requirements;
        return new ReadPlan(
            $eloquentPlan->id,
            $eloquentPlan->workspace_id,
            $eloquentPlan->description,
            $requirements,
            $eloquentPlan->added_at !== null,
            $eloquentPlan->launched_at !== null,
            $eloquentPlan->stopped_at !== null,
            $eloquentPlan->archived_at !== null,
        );
    }
}