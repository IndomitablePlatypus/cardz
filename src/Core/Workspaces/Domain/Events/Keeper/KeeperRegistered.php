<?php

namespace Cardz\Core\Workspaces\Domain\Events\Keeper;

use Codderz\Platypus\Contracts\Domain\AggregateEventInterface;
use Codderz\Platypus\Infrastructure\Support\Domain\AggregateEventTrait;

final class KeeperRegistered implements AggregateEventInterface
{
    use AggregateEventTrait;

    protected int $version = 1;

    public function version(): int
    {
        return $this->version;
    }

    private function __construct()
    {
    }

    public static function of(): self
    {
        return new self();
    }

    public static function from(array $data): static
    {
        return new static();
    }
}
