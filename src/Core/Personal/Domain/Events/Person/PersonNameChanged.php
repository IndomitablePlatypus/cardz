<?php

namespace Cardz\Core\Personal\Domain\Events\Person;

use Cardz\Core\Personal\Domain\Model\Person\Name;
use JetBrains\PhpStorm\Pure;

final class PersonNameChanged extends BasePersonDomainEvent
{
    private function __construct(
        public Name $name,
    ) {
    }

    #[Pure]
    public static function of(Name $name): self
    {
        return new self($name);
    }

    #[Pure]
    public static function from(array $data): static
    {
        return new self(Name::of($data['name']));
    }
}
