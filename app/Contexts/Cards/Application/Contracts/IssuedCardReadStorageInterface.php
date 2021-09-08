<?php

namespace App\Contexts\Cards\Application\Contracts;

use App\Contexts\Cards\Domain\ReadModel\IssuedCard;

interface IssuedCardReadStorageInterface
{
    public function find(string $cardId): ?IssuedCard;

    /**
     * @param string $planId
     * @return IssuedCard[]
     */
    public function allForPlanId(string $planId): array;
}
