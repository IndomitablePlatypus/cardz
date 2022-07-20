<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use Carbon\Carbon;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class CardSatisfied extends BaseCardDomainEvent
{
    private function __construct(public Carbon $satisfied)
    {
    }

    #[Pure]
    public static function of(Carbon $satisfied): self
    {
        return new self($satisfied);
    }

    public static function from(array $data): static
    {
        return new self(new Carbon($data['satisfied']));
    }
}
