<?php

namespace App\Contexts\Identity\Presentation\Controllers\Http\User;

use App\Contexts\Identity\Presentation\Controllers\Http\BaseController;
use App\Contexts\Identity\Presentation\Controllers\Http\User\Commands\RegisterUserRequest;
use App\Shared\Contracts\Commands\CommandBusInterface;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $command = $request->toCommand();
        $this->commandBus->dispatch($command);
        return $this->response($command->getUserId());
    }
}
