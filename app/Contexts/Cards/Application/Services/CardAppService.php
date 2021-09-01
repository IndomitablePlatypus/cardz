<?php

namespace App\Contexts\Cards\Application\Services;

use App\Contexts\Cards\Application\Contracts\CardRepositoryInterface;
use App\Contexts\Cards\Application\IntegrationEvents\{AchievementDismissed, AchievementNoted, CardBlocked, CardCompleted, CardIssued, CardRevoked,};
use App\Contexts\Cards\Domain\Model\Card\Card;
use App\Contexts\Cards\Domain\Model\Card\CardId;
use App\Contexts\Cards\Domain\Model\Card\Description;
use App\Contexts\Cards\Domain\Model\Card\RequirementId;
use App\Contexts\Cards\Domain\Model\Shared\CustomerId;
use App\Contexts\Cards\Domain\Model\Shared\PlanId;
use App\Contexts\Shared\Contracts\ReportingBusInterface;
use App\Contexts\Shared\Contracts\ServiceResultFactoryInterface;
use App\Contexts\Shared\Contracts\ServiceResultInterface;
use App\Contexts\Shared\Infrastructure\Support\ReportingServiceTrait;

class CardAppService
{
    use ReportingServiceTrait;

    public function __construct(
        private CardRepositoryInterface $cardRepository,
        private ReportingBusInterface $reportingBus,
        private ServiceResultFactoryInterface $resultFactory,
    ) {
    }

    public function issueCard(string $planId, string $customerId, string $description): ServiceResultInterface
    {
        $card = Card::make(
            CardId::make(),
            PlanId::of($planId),
            CustomerId::of($customerId),
            Description::of($description),
        );

        $card->issue();
        $this->cardRepository->persist($card);
        $result = $this->resultFactory->ok($card, new CardIssued($card->cardId));

        return $this->reportResult($result, $this->reportingBus);
    }

    public function completeCard(string $cardId): ServiceResultInterface
    {
        $card = $this->cardRepository->take(CardId::of($cardId));
        if ($card === null) {
            return $this->resultFactory->notFound("$cardId not found");
        }

        $card->complete();
        $this->cardRepository->persist($card);

        $result = $this->resultFactory->ok($card, new CardCompleted($card->cardId));
        return $this->reportResult($result, $this->reportingBus);
    }

    public function revokeCard(string $cardId): ServiceResultInterface
    {
        $card = $this->cardRepository->take(CardId::of($cardId));
        if ($card === null) {
            return $this->resultFactory->notFound("$cardId not found");
        }

        $card->revoke();
        $this->cardRepository->persist($card);

        $result = $this->resultFactory->ok($card, new CardRevoked($card->cardId));
        return $this->reportResult($result, $this->reportingBus);
    }

    public function blockCard(string $cardId): ServiceResultInterface
    {
        $card = $this->cardRepository->take(CardId::of($cardId));
        if ($card === null) {
            return $this->resultFactory->notFound("$cardId not found");
        }

        $card->block();
        $this->cardRepository->persist($card);

        $result = $this->resultFactory->ok($card, new CardBlocked($card->cardId));
        return $this->reportResult($result, $this->reportingBus);
    }

    public function noteAchievement(string $cardId, string $requirementId, string $achievementDescription): ServiceResultInterface
    {
        $card = $this->cardRepository->take(CardId::of($cardId));
        if ($card === null) {
            return $this->resultFactory->notFound("$cardId not found");
        }

        $card->noteAchievement(RequirementId::of($requirementId), $achievementDescription);
        $this->cardRepository->persist($card);

        $result = $this->resultFactory->ok($card, new AchievementNoted($card->cardId));
        return $this->reportResult($result, $this->reportingBus);
    }

    public function dismissAchievement(string $cardId, string $requirementId): ServiceResultInterface
    {
        $card = $this->cardRepository->take(CardId::of($cardId));
        if ($card === null) {
            return $this->resultFactory->notFound("$cardId not found");
        }

        $card->dismissAchievement(RequirementId::of($requirementId));
        $this->cardRepository->persist($card);

        $result = $this->resultFactory->ok($card, new AchievementDismissed($card->cardId));
        return $this->reportResult($result, $this->reportingBus);
    }

}
