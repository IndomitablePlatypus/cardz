<?php

namespace Codderz\Platypus\Infrastructure\Support\Domain;

use Carbon\Carbon;
use Codderz\Platypus\Contracts\Domain\AggregateRootInterface;
use Codderz\Platypus\Contracts\Domain\DomainEventInterface;
use Codderz\Platypus\Contracts\Messaging\EventInterface;
use Codderz\Platypus\Infrastructure\Support\ShortClassNameTrait;

abstract class DomainEvent implements EventInterface, DomainEventInterface
{
    use ShortClassNameTrait;

    private Carbon $at;

    protected function __construct(
        protected AggregateRootInterface $aggregateRoot
    ) {
        $this->at = Carbon::now();
    }

    public static function of(AggregateRootInterface $aggregateRoot): static
    {
        return new static($aggregateRoot);
    }

    public function at(): Carbon
    {
        return $this->at;
    }

    abstract public function with(): AggregateRootInterface;

    public function __toString(): string
    {
        return $this::shortName();
    }

    public function jsonSerialize()
    {
        return [
            'domainEvent' => $this::shortName(),
            'at' => $this->at,
            'with' => $this->aggregateRoot->jsonSerialize(),
        ];
    }
}
