<?php

namespace App\Contexts\Plans\Presentation\Controllers\Http\Plan;

use App\Contexts\Plans\Presentation\Controllers\Http\BaseController;
use App\Contexts\Plans\Presentation\Controllers\Http\Plan\Commands\{AddPlanRequest, ArchivePlanRequest, ChangePlanDescriptionRequest, LaunchPlanRequest, StopPlanRequest};
use App\Shared\Contracts\Commands\CommandBusInterface;
use Illuminate\Http\JsonResponse;

class PlanController extends BaseController
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function add(AddPlanRequest $request): JsonResponse
    {
        $command = $request->toCommand();
        $this->commandBus->dispatch($command);
        return $this->response($command->getPlanId());
    }

    public function launch(LaunchPlanRequest $request): JsonResponse
    {
        $this->commandBus->dispatch($request->toCommand());
        return $this->response($request->getPlanId());
    }

    public function stop(StopPlanRequest $request): JsonResponse
    {
        $this->commandBus->dispatch($request->toCommand());
        return $this->response($request->getPlanId());
    }

    public function archive(ArchivePlanRequest $request): JsonResponse
    {
        $this->commandBus->dispatch($request->toCommand());
        return $this->response($request->getPlanId());
    }

    public function changeDescription(ChangePlanDescriptionRequest $request): JsonResponse
    {
        $this->commandBus->dispatch($request->toCommand());
        return $this->response($request->getPlanId());
    }

}