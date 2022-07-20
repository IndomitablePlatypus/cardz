<?php

namespace Cardz\Core\Cards\Application\Consumers;

use Cardz\Core\Cards\Domain\Events\Card\AchievementDismissed as DomainAchievementDismissed;
use Cardz\Core\Cards\Domain\Events\Card\AchievementNoted as DomainAchievementNoted;
use Cardz\Core\Cards\Domain\Events\Card\CardBlocked as DomainCardBlocked;
use Cardz\Core\Cards\Domain\Events\Card\CardCompleted as DomainCardCompleted;
use Cardz\Core\Cards\Domain\Events\Card\CardIssued as DomainCardIssued;
use Cardz\Core\Cards\Domain\Events\Card\CardRevoked as DomainCardRevoked;
use Cardz\Core\Cards\Domain\Events\Card\CardSatisfactionWithdrawn as DomainCardSatisfactionWithdrawn;
use Cardz\Core\Cards\Domain\Events\Card\CardSatisfied as DomainCardSatisfied;
use Cardz\Core\Cards\Domain\Events\Card\CardUnblocked as DomainCardUnblocked;
use Cardz\Core\Cards\Domain\Events\Card\RequirementsAccepted as DomainRequirementsAccepted;
use Cardz\Core\Cards\Domain\Model\Card\Card;
use Cardz\Core\Cards\Domain\ReadModel\IssuedCard;
use Cardz\Core\Cards\Integration\Events\AchievementDismissed;
use Cardz\Core\Cards\Integration\Events\AchievementNoted;
use Cardz\Core\Cards\Integration\Events\CardBlocked;
use Cardz\Core\Cards\Integration\Events\CardCompleted;
use Cardz\Core\Cards\Integration\Events\CardIssued;
use Cardz\Core\Cards\Integration\Events\CardRevoked;
use Cardz\Core\Cards\Integration\Events\CardSatisfactionWithdrawn;
use Cardz\Core\Cards\Integration\Events\CardSatisfied;
use Cardz\Core\Cards\Integration\Events\CardUnblocked;
use Cardz\Core\Cards\Integration\Events\RequirementsAccepted;
use Codderz\Platypus\Contracts\Messaging\EventConsumerInterface;
use Codderz\Platypus\Contracts\Messaging\EventInterface;
use Codderz\Platypus\Contracts\Messaging\IntegrationEventBusInterface;

class CardChangedDomainEventConsumer implements EventConsumerInterface
{
    public function __construct(
        private IntegrationEventBusInterface $integrationEventBus,
    ) {
    }

    public function consumes(): array
    {
        return [
            DomainAchievementDismissed::class,
            DomainAchievementNoted::class,
            DomainCardBlocked::class,
            DomainCardCompleted::class,
            DomainCardIssued::class,
            DomainCardRevoked::class,
            DomainCardSatisfactionWithdrawn::class,
            DomainCardSatisfied::class,
            DomainCardUnblocked::class,
            DomainRequirementsAccepted::class,
        ];
    }

    public function handle(EventInterface $event): void
    {
        /** @var Card $card */
        $card = $event->with();

        $integrationEvent = match (true) {
            $event instanceof DomainAchievementDismissed => AchievementDismissed::of(IssuedCard::of($card)),
            $event instanceof DomainAchievementNoted => AchievementNoted::of(IssuedCard::of($card)),
            $event instanceof DomainCardBlocked => CardBlocked::of(IssuedCard::of($card)),
            $event instanceof DomainCardCompleted => CardCompleted::of(IssuedCard::of($card)),
            $event instanceof DomainCardIssued => CardIssued::of(IssuedCard::of($card)),
            $event instanceof DomainCardRevoked => CardRevoked::of(IssuedCard::of($card)),
            $event instanceof DomainCardSatisfactionWithdrawn => CardSatisfactionWithdrawn::of(IssuedCard::of($card)),
            $event instanceof DomainCardSatisfied => CardSatisfied::of(IssuedCard::of($card)),
            $event instanceof DomainCardUnblocked => CardUnblocked::of(IssuedCard::of($card)),
            $event instanceof DomainRequirementsAccepted => RequirementsAccepted::of(IssuedCard::of($card)),
            default => null,
        };

        if ($integrationEvent !== null) {
            $this->integrationEventBus->publish($integrationEvent);
        }
    }

}
