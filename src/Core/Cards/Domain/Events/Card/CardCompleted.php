<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use Carbon\Carbon;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class CardCompleted extends BaseCardDomainEvent
{
    private function __construct(public Carbon $completed)
    {
    }

    #[Pure]
    public static function of(Carbon $completed): self
    {
        return new self($completed);
    }

    public static function from(array $data): static
    {
        return new self(new Carbon($data['completed']));
    }
}
