<?php

namespace Cardz\Core\Personal\Domain\Events\Person;

use Carbon\Carbon;
use Cardz\Core\Personal\Domain\Model\Person\Name;
use JetBrains\PhpStorm\Pure;

final class PersonJoined extends BasePersonDomainEvent
{
    private function __construct(
        public Name $name,
        public Carbon $joined,
    ) {
    }

    #[Pure]
    public static function of(Name $name, Carbon $joined): self
    {
        return new self($name, $joined);
    }

    public static function from(array $data): static
    {
        return new self(Name::of($data['name']), new Carbon($data['joined']));
    }
}
