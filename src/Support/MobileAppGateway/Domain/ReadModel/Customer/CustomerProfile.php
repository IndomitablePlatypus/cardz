<?php

namespace Cardz\Support\MobileAppGateway\Domain\ReadModel\Customer;

use Codderz\Platypus\Infrastructure\Support\ArrayPresenterTrait;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

final class CustomerProfile implements JsonSerializable
{
    use ArrayPresenterTrait;

    private function __construct(
        public string $profileId,
        public string $name,
        public string $phone,
    ) {
    }

    #[Pure]
    public static function make(
        string $profileId,
        string $name,
        string $phone,
    ): self {
        return new self(
            $profileId,
            $name,
            $phone,
        );
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
