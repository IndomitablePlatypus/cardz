<?php

namespace Cardz\Core\Cards\Domain\ReadModel;

use Carbon\Carbon;
use Cardz\Core\Cards\Domain\Model\Card\Achievements;
use Cardz\Core\Cards\Domain\Model\Card\Card;
use Codderz\Platypus\Infrastructure\Support\ArrayPresenterTrait;

final class ReadCard
{
    use ArrayPresenterTrait;

    public function __construct(
        public string $cardId,
        public string $planId,
        public string $customerId,
        public string $description,
        public ?Carbon $issued,
        public ?Carbon $satisfied,
        public ?Carbon $completed,
        public ?Carbon $revoked,
        public ?Carbon $blocked,
        public Achievements $achievements,
        public Achievements $requirements,
    ) {
    }

    public static function of(Card $card): self
    {
        $cardData = $card->toArray(except: ['methodPrefix', 'events']);
        $cardData['achievements'] = Achievements::of(...$cardData['achievements']);
        $cardData['requirements'] = Achievements::of(...$cardData['requirements']);
        return new self(...$cardData);
    }
}
