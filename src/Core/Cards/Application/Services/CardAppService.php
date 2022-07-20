<?php

namespace Cardz\Core\Cards\Application\Services;

use Cardz\Core\Cards\Application\Commands\AcceptRequirements;
use Cardz\Core\Cards\Application\Commands\BlockCard;
use Cardz\Core\Cards\Application\Commands\CardCommandInterface;
use Cardz\Core\Cards\Application\Commands\CompleteCard;
use Cardz\Core\Cards\Application\Commands\DismissAchievement;
use Cardz\Core\Cards\Application\Commands\FixAchievementDescription;
use Cardz\Core\Cards\Application\Commands\IssueCard;
use Cardz\Core\Cards\Application\Commands\NoteAchievement;
use Cardz\Core\Cards\Application\Commands\RevokeCard;
use Cardz\Core\Cards\Application\Commands\UnblockCard;
use Cardz\Core\Cards\Domain\Exceptions\CardNotFoundExceptionInterface;
use Cardz\Core\Cards\Domain\Exceptions\PlanNotFoundExceptionInterface;
use Cardz\Core\Cards\Domain\Model\Card\Card;
use Cardz\Core\Cards\Domain\Model\Card\CardId;
use Cardz\Core\Cards\Domain\Persistence\Contracts\CardRepositoryInterface;
use Cardz\Core\Cards\Domain\Persistence\Contracts\PlanRepositoryInterface;
use Cardz\Core\Cards\Infrastructure\Messaging\DomainEventBusInterface;

class CardAppService
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
        private PlanRepositoryInterface $planRepository,
        private DomainEventBusInterface $domainEventBus,
    ) {
    }

    /**
     * @throws PlanNotFoundExceptionInterface
     */
    public function issue(IssueCard $command): CardId
    {
        $plan = $this->planRepository->take($command->getPlanId());
        return $this->release($plan->issueCard($command->getCardId(), $command->getCustomerId()));
    }

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function complete(CompleteCard $command): CardId
    {
        $card = $this->card($command);
        return $this->release($card->complete());
    }

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function revoke(RevokeCard $command): CardId
    {
        $card = $this->card($command);
        return $this->release($card->revoke());
    }

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function block(BlockCard $command): CardId
    {
        $card = $this->card($command);
        return $this->release($card->block());
    }

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function unblock(UnblockCard $command): CardId
    {
        $card = $this->card($command);
        return $this->release($card->unblock());
    }

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function noteAchievement(NoteAchievement $command): CardId
    {
        $card = $this->card($command);
        return $this->release($card->noteAchievement($command->getAchievement()));
    }

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function dismissAchievement(DismissAchievement $command): CardId
    {
        $card = $this->card($command);
        return $this->release($card->dismissAchievement($command->getAchievementId()));
    }

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function fixAchievementDescription(FixAchievementDescription $command): CardId
    {
        $card = $this->card($command);
        return $this->release($card->fixAchievementDescription($command->getAchievement()));
    }

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function acceptRequirements(AcceptRequirements $command): CardId
    {
        $card = $this->card($command);
        return $this->release($card->acceptRequirements($command->getRequirements()));
    }

    private function release(Card $card): CardId
    {
        $events = $this->cardRepository->store($card);
        $this->domainEventBus->publish(...$events);
        return $card->cardId;
    }

    /**
     * @throws CardNotFoundExceptionInterface
     */
    private function card(CardCommandInterface $command): Card
    {
        return $this->cardRepository->restore($command->getCardId());
    }
}
