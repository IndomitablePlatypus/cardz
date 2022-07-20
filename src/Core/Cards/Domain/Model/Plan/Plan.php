<?php

namespace Cardz\Core\Cards\Domain\Model\Plan;

use Carbon\Carbon;
use Cardz\Core\Cards\Domain\Model\Card\Achievements;
use Cardz\Core\Cards\Domain\Model\Card\Card;
use Cardz\Core\Cards\Domain\Model\Card\CardId;
use Cardz\Core\Cards\Domain\Model\Card\CustomerId;
use Cardz\Core\Cards\Domain\Model\Card\Description;
use Codderz\Platypus\Contracts\Domain\AggregateRootInterface;
use Codderz\Platypus\Infrastructure\Support\Domain\AggregateRootTrait;
use JetBrains\PhpStorm\Pure;

final class Plan implements AggregateRootInterface
{
    use AggregateRootTrait;

    /**
     * @var Requirement[]
     */
    private array $requirements;

    #[Pure]
    private function __construct(
        private PlanId $planId,
        private string $name,
        private string $description,
        Requirement ...$requirements
    ) {
        $this->requirements = $requirements;
    }

    public static function restore(string $planId, string $name, string $description, Requirement ...$requirements): self
    {
        return new self(PlanId::of($planId), $name, $description, ...$requirements);
    }

    public function issueCard(CardId $cardId, CustomerId $customerId): Card
    {
        $card = Card::draft($cardId)->issue($this->planId, $customerId, Description::of($this->description), Carbon::now());
        $card->acceptRequirements(Achievements::from(...$this->requirements));
        return $card;
    }
}
