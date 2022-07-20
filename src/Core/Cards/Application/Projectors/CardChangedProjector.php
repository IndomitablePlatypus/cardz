<?php

namespace Cardz\Core\Cards\Application\Projectors;

use Cardz\Core\Cards\Domain\Events\Card\{AchievementDescriptionFixed,
    AchievementDismissed,
    AchievementNoted,
    CardBlocked,
    CardCompleted,
    CardIssued,
    CardRevoked,
    CardSatisfactionWithdrawn,
    CardSatisfied,
    CardUnblocked,
    RequirementsAccepted,
};
use Cardz\Core\Cards\Domain\Model\Card\Card;
use Cardz\Core\Cards\Domain\ReadModel\ReadCard;
use Cardz\Core\Cards\Infrastructure\ReadStorage\Contracts\CardReadStorageInterface;
use Codderz\Platypus\Contracts\Messaging\EventConsumerInterface;
use Codderz\Platypus\Contracts\Messaging\EventInterface;

final class CardChangedProjector implements EventConsumerInterface
{
    public function __construct(
        private CardReadStorageInterface $cardReadStorage,
    ) {
    }

    public function consumes(): array
    {
        return [
            AchievementDescriptionFixed::class,
            AchievementDismissed::class,
            AchievementNoted::class,
            CardBlocked::class,
            CardCompleted::class,
            CardIssued::class,
            CardRevoked::class,
            CardSatisfactionWithdrawn::class,
            CardSatisfied::class,
            CardUnblocked::class,
            RequirementsAccepted::class,
        ];
    }

    public function handle(EventInterface $event): void
    {
        /** @var Card $issuedCard */
        $card = $event->with();
        $this->cardReadStorage->persist(ReadCard::of($card));
    }

}
