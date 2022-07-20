<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class AchievementDismissed extends BaseCardDomainEvent
{
    private function __construct(
        public string $achievementId,
    ) {
    }

    #[Pure]
    public static function of(string $achievementId): self
    {
        return new self($achievementId);
    }

    public static function from(array $data): static
    {
        return new self($data['achievementId']);
    }
}
