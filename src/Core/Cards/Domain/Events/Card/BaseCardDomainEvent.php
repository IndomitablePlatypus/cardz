<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use Codderz\Platypus\Contracts\Domain\AggregateEventInterface;
use Codderz\Platypus\Infrastructure\Support\Domain\AggregateEventTrait;

abstract class BaseCardDomainEvent implements AggregateEventInterface
{
    use AggregateEventTrait;

    protected int $version = 1;

    public function version(): int
    {
        return $this->version;
    }
}
