<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use Cardz\Core\Cards\Domain\Model\Card\Achievement;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class AchievementDescriptionFixed extends BaseCardDomainEvent
{
    private function __construct(public Achievement $achievement)
    {
    }

    #[Pure]
    public static function of(Achievement $achievement): self
    {
        return new self($achievement);
    }

    public static function from(array $data): static
    {
        return new self(Achievement::of(...$data['achievement']));
    }
}
