<?php

namespace Cardz\Core\Cards\Domain\Events\Card;

use Carbon\Carbon;
use Cardz\Core\Cards\Domain\Model\Card\CardId;
use Cardz\Core\Cards\Domain\Model\Card\CustomerId;
use Cardz\Core\Cards\Domain\Model\Card\Description;
use Cardz\Core\Cards\Domain\Model\Plan\PlanId;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class CardIssued extends BaseCardDomainEvent
{
    private function __construct(
        public PlanId $planId,
        public CustomerId $customerId,
        public Description $description,
        public Carbon $issued,
    ) {
    }

    #[Pure]
    public static function of(
        PlanId $planId,
        CustomerId $customerId,
        Description $description,
        Carbon $issued,
    ): self {
        return new self($planId, $customerId, $description, $issued);
    }

    public static function from(array $data): static
    {
        return new self(
            PlanId::of($data['planId']),
            CustomerId::of($data['customerId']),
            Description::of($data['description']),
            new Carbon($data['issued']),
        );
    }

}
