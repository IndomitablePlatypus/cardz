<?php

namespace Cardz\Core\Cards\Domain\Model\Plan;

use Codderz\Platypus\Contracts\Domain\ValueObjectInterface;
use Codderz\Platypus\Infrastructure\Support\ArrayPresenterTrait;
use JetBrains\PhpStorm\Pure;

final class Requirement implements ValueObjectInterface
{
    use ArrayPresenterTrait;

    private function __construct(
        public string $requirementId,
        public string $description,
    ) {
    }

    #[Pure]
    public static function of(string $requirementId, string $description): self
    {
        return new self($requirementId, $description);
    }
}
