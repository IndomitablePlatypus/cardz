<?php

namespace Cardz\Core\Cards\Application\Commands;

use Cardz\Core\Cards\Domain\Model\Card\CardId;
use Cardz\Core\Cards\Domain\Model\Card\CustomerId;
use Cardz\Core\Cards\Domain\Model\Plan\PlanId;

final class IssueCard implements CardCommandInterface
{
    private function __construct(
        private string $cardId,
        private string $planId,
        private string $customerId,
    ) {
    }

    public static function of(string $planId, string $customerId): self
    {
        return new self(CardId::makeValue(), $planId, $customerId);
    }

    public function getCardId(): CardId
    {
        return CardId::of($this->cardId);
    }

    public function getPlanId(): PlanId
    {
        return PlanId::of($this->planId);
    }

    public function getCustomerId(): CustomerId
    {
        return CustomerId::of($this->customerId);
    }

}
