<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use Carbon\Carbon;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class CardBlocked extends BaseCardDomainEvent
{
    private function __construct(public Carbon $blocked)
    {
    }

    #[Pure]
    public static function of(Carbon $blocked): self
    {
        return new self($blocked);
    }

    public static function from(array $data): static
    {
        return new self(new Carbon($data['blocked']));
    }
}
