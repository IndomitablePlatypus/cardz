<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use Carbon\Carbon;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class CardRevoked extends BaseCardDomainEvent
{
    private function __construct(public Carbon $revoked)
    {
    }

    #[Pure]
    public static function of(Carbon $revoked): self
    {
        return new self($revoked);
    }

    public static function from(array $data): static
    {
        return new self(new Carbon($data['revoked']));
    }
}
