<?php

namespace App\Contexts\MobileAppBack\Application\Services\Workspace;

use App\Contexts\MobileAppBack\Application\Services\Workspace\Policies\AssertCardForKeeper;
use App\Contexts\MobileAppBack\Application\Services\Workspace\Policies\AssertCardInWorkspace;
use App\Contexts\MobileAppBack\Application\Services\Workspace\Policies\AssertPlanInWorkspace;
use App\Contexts\MobileAppBack\Application\Services\Workspace\Policies\AssertWorkspaceForKeeper;
use App\Contexts\MobileAppBack\Domain\Model\Card\CardId;
use App\Contexts\MobileAppBack\Domain\Model\Collaboration\KeeperId;
use App\Contexts\MobileAppBack\Domain\Model\Workspace\PlanId;
use App\Contexts\MobileAppBack\Domain\Model\Workspace\WorkspaceId;
use App\Contexts\MobileAppBack\Infrastructure\ACL\Cards\CardsAdapter;
use App\Contexts\MobileAppBack\Infrastructure\ReadStorage\Shared\Contracts\IssuedCardReadStorageInterface;
use App\Shared\Contracts\PolicyEngineInterface;
use App\Shared\Contracts\ServiceResultFactoryInterface;
use App\Shared\Contracts\ServiceResultInterface;

class CardService
{
    public function __construct(
        private IssuedCardReadStorageInterface $issuedCardReadStorage,
        private CardsAdapter $cardsAdapter,
        private PolicyEngineInterface $policyEngine,
        private ServiceResultFactoryInterface $serviceResultFactory,
    ) {
    }

    public function getCard(string $keeperId, string $cardId): ServiceResultInterface
    {
        if (!AssertCardForKeeper::of(CardId::of($cardId), KeeperId::of($keeperId))->assert()) {
            return $this->serviceResultFactory->violation("Card $cardId is not for keeper $keeperId");
        }

        return $this->getIssuedCardResult($cardId);
    }

    private function getIssuedCardResult(string $cardId): ServiceResultInterface
    {
        $card = $this->issuedCardReadStorage->find($cardId);
        if ($card === null) {
            return $this->serviceResultFactory->violation("Card $cardId not found");
        }

        return $this->serviceResultFactory->ok($card);
    }

    public function issue(string $keeperId, string $workspaceId, string $planId, string $customerId, string $description): ServiceResultInterface
    {
        return $this->policyEngine->passTrough(
            function () use ($planId, $customerId, $description) {
                $result = $this->cardsAdapter->issueCard($planId, $customerId, $description);
                if ($result->isNotOk()) {
                    return $result;
                }
                $cardId = $result->getPayload();
                return $this->getIssuedCardResult($cardId);
            },

            AssertWorkspaceForKeeper::of(WorkspaceId::of($workspaceId), KeeperId::of($keeperId)),
            AssertPlanInWorkspace::of(PlanId::of($planId), WorkspaceId::of($workspaceId)),
        );
    }

    public function complete(string $keeperId, string $workspaceId, string $cardId): ServiceResultInterface
    {
        return $this->policyEngine->passTrough(
            function () use ($cardId) {
                $result = $this->cardsAdapter->completeCard($cardId);
                if ($result->isNotOk()) {
                    return $result;
                }
                return $this->getIssuedCardResult($cardId);
            },

            AssertWorkspaceForKeeper::of(WorkspaceId::of($workspaceId), KeeperId::of($keeperId)),
            AssertCardInWorkspace::of(CardId::of($cardId), WorkspaceId::of($workspaceId)),
        );
    }

    public function revoke(string $keeperId, string $workspaceId, string $cardId): ServiceResultInterface
    {
        return $this->policyEngine->passTrough(
            function () use ($cardId) {
                $result = $this->cardsAdapter->revokeCard($cardId);
                if ($result->isNotOk()) {
                    return $result;
                }
                return $this->getIssuedCardResult($cardId);
            },

            AssertWorkspaceForKeeper::of(WorkspaceId::of($workspaceId), KeeperId::of($keeperId)),
            AssertCardInWorkspace::of(CardId::of($cardId), WorkspaceId::of($workspaceId)),
        );
    }

    public function block(string $keeperId, string $workspaceId, string $cardId): ServiceResultInterface
    {
        return $this->policyEngine->passTrough(
            function () use ($cardId) {
                $result = $this->cardsAdapter->blockCard($cardId);
                if ($result->isNotOk()) {
                    return $result;
                }
                return $this->getIssuedCardResult($cardId);
            },

            AssertWorkspaceForKeeper::of(WorkspaceId::of($workspaceId), KeeperId::of($keeperId)),
            AssertCardInWorkspace::of(CardId::of($cardId), WorkspaceId::of($workspaceId)),
        );
    }

    public function unblock(string $keeperId, string $workspaceId, string $cardId): ServiceResultInterface
    {
        return $this->policyEngine->passTrough(
            function () use ($cardId) {
                $result = $this->cardsAdapter->unblockCard($cardId);
                if ($result->isNotOk()) {
                    return $result;
                }

                return $this->getIssuedCardResult($cardId);
            },

            AssertWorkspaceForKeeper::of(WorkspaceId::of($workspaceId), KeeperId::of($keeperId)),
            AssertCardInWorkspace::of(CardId::of($cardId), WorkspaceId::of($workspaceId)),
        );
    }

    public function noteAchievement(string $keeperId, string $workspaceId, string $cardId, string $achievementId, string $description): ServiceResultInterface
    {
        return $this->policyEngine->passTrough(
            function () use ($cardId, $achievementId, $description) {
                $result = $this->cardsAdapter->noteAchievement($cardId, $achievementId, $description);
                if ($result->isNotOk()) {
                    return $result;
                }
                return $this->getIssuedCardResult($cardId);
            },

            AssertWorkspaceForKeeper::of(WorkspaceId::of($workspaceId), KeeperId::of($keeperId)),
            AssertCardInWorkspace::of(CardId::of($cardId), WorkspaceId::of($workspaceId)),
        );
    }

    public function dismissAchievement(string $keeperId, string $workspaceId, string $cardId, string $achievementId, string $description): ServiceResultInterface
    {
        return $this->policyEngine->passTrough(
            function () use ($cardId, $achievementId, $description) {
                $result = $this->cardsAdapter->dismissAchievement($cardId, $achievementId, $description);
                if ($result->isNotOk()) {
                    return $result;
                }
                return $this->getIssuedCardResult($cardId);
            },

            AssertWorkspaceForKeeper::of(WorkspaceId::of($workspaceId), KeeperId::of($keeperId)),
            AssertCardInWorkspace::of(CardId::of($cardId), WorkspaceId::of($workspaceId)),
        );
    }

}
