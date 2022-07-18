<?php

namespace Cardz\Core\Cards\Tests\Support\Builders;

use Carbon\Carbon;
use Cardz\Core\Cards\Domain\Model\Card\Achievement;
use Cardz\Core\Cards\Domain\Model\Card\Achievements;
use Cardz\Core\Cards\Domain\Model\Card\Card;
use Cardz\Core\Cards\Domain\Model\Card\CardId;
use Cardz\Core\Cards\Domain\Model\Card\CustomerId;
use Cardz\Core\Cards\Domain\Model\Card\Description;
use Cardz\Core\Cards\Domain\Model\Plan\PlanId;
use Cardz\Core\Cards\Domain\Model\Plan\Requirement;
use Codderz\Platypus\Infrastructure\Tests\BaseBuilder;

final class CardBuilder extends BaseBuilder
{
    public CardId $cardId;

    public PlanId $planId;

    public CustomerId $customerId;

    public Description $description;

    public ?Carbon $issued = null;

    public ?Carbon $satisfied = null;

    public ?Carbon $completed = null;

    public ?Carbon $revoked = null;

    public ?Carbon $blocked = null;

    public Achievements $achievements;

    public Achievements $requirements;

    public function build(): Card
    {
        return $this->forceConstruct(Card::class);
    }

    public function withRequirements(Requirement ... $requirements): self
    {
        $this->requirements = Achievements::from(...$requirements);
        return $this;
    }

    public function withAchievements(Achievement ... $achievements): self
    {
        $achievementData = [];
        foreach ($achievements as $achievement) {
            $achievementData[] = $achievement->toArray();
        }
        $this->achievements = Achievements::of(...$achievementData);
        return $this;
    }

    public function withCustomerId(string $customerId): self
    {
        $this->customerId = CustomerId::of($customerId);
        return $this;
    }

    public function withPlanId(string $planId): self
    {
        $this->planId = PlanId::of($planId);
        return $this;
    }

    public function generate(): static
    {
        $this->cardId = CardId::make();
        $this->planId = PlanId::make();
        $this->customerId = CustomerId::make();
        $this->description = Description::of($this->faker->text());
        $this->issued = Carbon::now();
        $this->satisfied = null;
        $this->completed = null;
        $this->revoked = null;
        $this->blocked = null;
        $this->achievements = Achievements::of();
        $this->requirements = Achievements::of();
        return $this;
    }
}
