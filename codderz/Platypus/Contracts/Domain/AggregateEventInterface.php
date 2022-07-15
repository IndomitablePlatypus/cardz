<?php

namespace Codderz\Platypus\Contracts\Domain;

use Carbon\Carbon;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Contracts\Messaging\EventInterface;

interface AggregateEventInterface extends EventInterface
{
    public static function shortName(): string;

    public static function from(array $data): static;

    public function channel(): string;

    public function name(): string;

    public function stream(): GenericIdInterface;

    public function version(): int;

    public function at(): Carbon;

    public function changeset(): array;

    public function in(EventDrivenAggregateRootInterface $aggregateRoot): static;

    public function with(): EventDrivenAggregateRootInterface;

    public function toArray(): array;
}
