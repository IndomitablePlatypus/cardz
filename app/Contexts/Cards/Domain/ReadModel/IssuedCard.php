<?php

namespace App\Contexts\Cards\Domain\ReadModel;

use App\Shared\Infrastructure\Support\ArrayPresenterTrait;
use JetBrains\PhpStorm\Pure;

final class IssuedCard
{
    use ArrayPresenterTrait;

    private function __construct(
        public string $cardId,
        public string $planId,
        public string $customerId,
        public bool $satisfied,
        public bool $completed,
        public bool $revoked,
        public bool $blocked,
        public array $achievements,
        public array $requirements,
    ) {
    }

    /**
     * @param string[] $achievements
     * @param string[] $requirements
     */
    #[Pure]
    public static function make(
        string $cardId,
        string $planId,
        string $customerId,
        bool $satisfied,
        bool $completed,
        bool $revoked,
        bool $blocked,
        array $achievements,
        array $requirements,
    ): self {
        return new self(
            $cardId,
            $planId,
            $customerId,
            $satisfied,
            $completed,
            $revoked,
            $blocked,
            $achievements,
            $requirements,
        );
    }
}
