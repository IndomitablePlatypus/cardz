<?php

namespace App\Contexts\MobileAppBack\Infrastructure\ACL\Cards;

use App\Contexts\Cards\Application\Services\BlockedCardAppService;
use App\Contexts\Cards\Application\Services\CardAppService;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\IssuedCardReadStorage;
use App\Contexts\Shared\Contracts\ServiceResultFactoryInterface;
use App\Contexts\Shared\Contracts\ServiceResultInterface;

class CardsAdapter
{
    //ToDo: здесь могло бы быть обращение по HTTP
    public function __construct(
        private CardAppService $cardAppService,
        private BlockedCardAppService $blockedCardAppService,
        private ServiceResultFactoryInterface $serviceResultFactory,
    ) {
    }

    public function issueCard(string $planId, string $customerId, string $description): ServiceResultInterface
    {
        $result = $this->cardAppService->issueCard($planId, $customerId, $description);
        if ($result->isNotOk()){
            return $result;
        }
        $cardId = (string) $result->getPayload()->cardId;
        return $this->serviceResultFactory->ok($cardId);
    }

    public function completeCard(string $cardId): ServiceResultInterface
    {
        $result = $this->cardAppService->completeCard($cardId);
        if ($result->isNotOk()){
            return $result;
        }
        return $this->serviceResultFactory->ok();
    }

    public function revokeCard(string $cardId): ServiceResultInterface
    {
        $result = $this->cardAppService->revokeCard($cardId);
        if ($result->isNotOk()){
            return $result;
        }
        return $this->serviceResultFactory->ok();
    }

    public function blockCard(string $cardId): ServiceResultInterface
    {
        $result = $this->cardAppService->blockCard($cardId);
        if ($result->isNotOk()){
            return $result;
        }
        return $this->serviceResultFactory->ok();
    }

    public function unblockCard(string $cardId): ServiceResultInterface
    {
        $result = $this->blockedCardAppService->unblockCard($cardId);
        if ($result->isNotOk()){
            return $result;
        }
        return $this->serviceResultFactory->ok();
    }

    public function noteAchievement(string $cardId, string $description): ServiceResultInterface
    {
        $result = $this->cardAppService->noteAchievement($cardId, $description);
        if ($result->isNotOk()){
            return $result;
        }
        return $this->serviceResultFactory->ok();
    }

    public function dismissAchievement(string $cardId, string $description): ServiceResultInterface
    {
        $result = $this->cardAppService->dismissAchievement($cardId, $description);
        if ($result->isNotOk()){
            return $result;
        }
        return $this->serviceResultFactory->ok();
    }

}
