<?php

namespace App\Contexts\Auth\Domain\ReadModel;

use App\Shared\Infrastructure\Support\ArrayPresenterTrait;

class Token
{
    use ArrayPresenterTrait;

    public function __construct(
        public string $tokenableId,
        public string $token,
    ) {
    }
}
