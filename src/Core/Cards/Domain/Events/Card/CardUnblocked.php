<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use Carbon\Carbon;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class CardUnblocked extends BaseCardDomainEvent
{
    public ?Carbon $blocked = null;

    #[Pure]
    public static function of(): self
    {
        return new self();
    }

    public static function from(array $data): static
    {
        return new self();
    }
}
