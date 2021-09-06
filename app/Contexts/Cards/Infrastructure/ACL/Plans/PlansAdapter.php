<?php

namespace App\Contexts\Cards\Infrastructure\ACL\Plans;

use App\Contexts\Plans\Application\Services\ReadPlanAppService;
use App\Contexts\Shared\Contracts\ServiceResultFactoryInterface;
use App\Contexts\Shared\Contracts\ServiceResultInterface;

class PlansAdapter
{
    //ToDo: сцепление с соседним контекстом. При переходе на микросервисы, можно например, API юзать
    public function __construct(
        private ReadPlanAppService $readPlanAppService,
        private ServiceResultFactoryInterface $serviceResultFactory,
    ) {
    }

    public function getRequirements(string $planId): ServiceResultInterface
    {
        $result = $this->readPlanAppService->getReadPlan($planId);
        if (!$result->isOk()) {
            return $result;
        }
        $payload = $result->getPayload();
        return $this->serviceResultFactory->ok($payload['requirements']);
    }
}