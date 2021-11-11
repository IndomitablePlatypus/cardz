<?php

namespace App\Contexts\Workspaces\Presentation\Controllers\Http\Workspace;

use App\Contexts\Workspaces\Presentation\Controllers\Http\BaseController;
use App\Contexts\Workspaces\Presentation\Controllers\Http\Workspace\Commands\{AddWorkspaceRequest, ChangeWorkspaceProfileRequest};
use App\Shared\Contracts\Commands\CommandBusInterface;
use Illuminate\Http\JsonResponse;

class WorkspaceController extends BaseController
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function add(AddWorkspaceRequest $request): JsonResponse
    {
        $command = $request->toCommand();
        $this->commandBus->dispatch($command);
        return $this->response($command->getWorkspaceId());
    }

    public function changeProfile(ChangeWorkspaceProfileRequest $request): JsonResponse
    {
        $command = $request->toCommand();
        $this->commandBus->dispatch($command);
        return $this->response($command->getWorkspaceId());
    }
}
