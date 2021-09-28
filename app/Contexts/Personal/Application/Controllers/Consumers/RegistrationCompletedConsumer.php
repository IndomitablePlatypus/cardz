<?php

namespace App\Contexts\Personal\Application\Controllers\Consumers;

use App\Contexts\Auth\Application\IntegrationEvents\RegistrationCompleted;
use App\Contexts\Personal\Application\Services\PersonAppService;
use App\Models\User as EloquentUser;
use App\Shared\Contracts\Informable;
use App\Shared\Contracts\Reportable;

final class RegistrationCompletedConsumer implements Informable
{
    public function __construct(
        private PersonAppService $personAppService
    ) {
    }

    public function accepts(Reportable $reportable): bool
    {
        //ToDo: тут связь контекстов
        return $reportable instanceof RegistrationCompleted;
    }

    public function inform(Reportable $reportable): void
    {
        /** @var RegistrationCompleted $event */
        $event = $reportable;
        //ToDo: тут перед этим readmodel типа пишется или как? Или это считать как API-запрос в соседний контекст..
        $user = EloquentUser::query()->find($event->id());
        if ($user) {
            $this->personAppService->join($user->id, $user->name);
        }
    }
}
