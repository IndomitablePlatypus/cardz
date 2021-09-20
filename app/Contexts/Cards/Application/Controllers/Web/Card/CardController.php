<?php

namespace App\Contexts\Cards\Application\Controllers\Web\Card;

use App\Contexts\Cards\Application\Controllers\Web\BaseController;
use App\Contexts\Cards\Application\Controllers\Web\Card\Commands\{DismissAchievementRequest,
    NoteAchievementRequest,
    BlockCardRequest,
    CompleteCardRequest,
    IssueCardRequest,
    RevokeCardRequest};
use App\Contexts\Cards\Application\Controllers\Web\Card\Queries\GetIssuedCardRequest;
use App\Contexts\Cards\Application\Services\CardAppService;
use App\Contexts\Cards\Application\Services\ReadIssuedCardAppService;
use Illuminate\Http\JsonResponse;

class CardController extends BaseController
{
    public function __construct(
        private CardAppService $cardAppService,
        private ReadIssuedCardAppService $readIssuedCardAppService,
    ) {
    }

    public function issue(IssueCardRequest $request)
    {
        return $this->response($this->cardAppService->issueCard(
            $request->planId,
            $request->customerId,
            $request->description,
        ));
    }

    public function complete(CompleteCardRequest $request): JsonResponse
    {
        return $this->response($this->cardAppService->completeCard(
            $request->cardId,
        ));
    }

    public function revoke(RevokeCardRequest $request): JsonResponse
    {
        return $this->response($this->cardAppService->revokeCard(
            $request->cardId,
        ));
    }

    public function block(BlockCardRequest $request): JsonResponse
    {
        return $this->response($this->cardAppService->blockCard(
            $request->cardId,
        ));
    }

    public function addAchievement(NoteAchievementRequest $request): JsonResponse
    {
        return $this->response($this->cardAppService->noteAchievement(
            $request->cardId,
            $request->achievementId,
            $request->achievementDescription,
        ));
    }

    public function removeAchievement(DismissAchievementRequest $request): JsonResponse
    {
        return $this->response($this->cardAppService->dismissAchievement(
            $request->cardId,
            $request->achievementId,
        ));
    }

    public function getIssuedCard(GetIssuedCardRequest $request): JsonResponse
    {
        return $this->response($this->readIssuedCardAppService->getIssuedCard(
            $request->cardId,
        ));
    }
}
