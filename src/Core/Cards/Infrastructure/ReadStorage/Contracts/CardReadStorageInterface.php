<?php

namespace Cardz\Core\Cards\Infrastructure\ReadStorage\Contracts;

use Cardz\Core\Cards\Domain\ReadModel\ReadCard;

interface CardReadStorageInterface
{
    public function persist(ReadCard $card): void;

    public function take(?string $cardId): ?ReadCard;

}
