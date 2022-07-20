<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use Cardz\Core\Cards\Domain\Model\Card\Achievements;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class RequirementsAccepted extends BaseCardDomainEvent
{
    private function __construct(public Achievements $requirements)
    {
    }

    #[Pure]
    public static function of(Achievements $requirements): self
    {
        return new self($requirements);
    }

    public static function from(array $data): static
    {
        return new self(Achievements::of(...$data['requirements']));
    }
}
