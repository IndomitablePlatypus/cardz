<?php

namespace Cardz\Core\Cards\Domain\Model\Card;

use Carbon\Carbon;
use Cardz\Core\Cards\Domain\Events\Card\AchievementDescriptionFixed;
use Cardz\Core\Cards\Domain\Events\Card\AchievementDismissed;
use Cardz\Core\Cards\Domain\Events\Card\AchievementNoted;
use Cardz\Core\Cards\Domain\Events\Card\CardBlocked;
use Cardz\Core\Cards\Domain\Events\Card\CardCompleted;
use Cardz\Core\Cards\Domain\Events\Card\CardIssued;
use Cardz\Core\Cards\Domain\Events\Card\CardRevoked;
use Cardz\Core\Cards\Domain\Events\Card\CardSatisfactionWithdrawn;
use Cardz\Core\Cards\Domain\Events\Card\CardSatisfied;
use Cardz\Core\Cards\Domain\Events\Card\CardUnblocked;
use Cardz\Core\Cards\Domain\Events\Card\RequirementsAccepted;
use Cardz\Core\Cards\Domain\Exceptions\InvalidCardStateException;
use Cardz\Core\Cards\Domain\Model\Plan\PlanId;
use Codderz\Platypus\Contracts\Domain\EventDrivenAggregateRootInterface;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Support\Domain\EventDrivenAggregateRootTrait;

final class Card implements EventDrivenAggregateRootInterface
{
    use EventDrivenAggregateRootTrait;

    public PlanId $planId;

    public CustomerId $customerId;

    public Description $description;

    private ?Carbon $issued = null;

    private ?Carbon $satisfied = null;

    private ?Carbon $completed = null;

    private ?Carbon $revoked = null;

    private ?Carbon $blocked = null;

    private Achievements $achievements;

    private Achievements $requirements;

    protected static function idFromEventStream(GenericIdInterface $id): CardId
    {
        return CardId::of($id);
    }

    public function __construct(public CardId $cardId)
    {
        $this->achievements = Achievements::of();
        $this->requirements = Achievements::of();
    }

    public static function draft(CardId $cardId): self
    {
        return new self($cardId);
    }

    public function issue(PlanId $planId, CustomerId $customerId, Description $description, Carbon $issued): self
    {
        return $this->recordThat(CardIssued::of($planId, $customerId, $description, $issued));
    }

    public function id(): CardId
    {
        return $this->cardId;
    }

    public function complete(): self
    {
        if ($this->isCompleted() || $this->isRevoked() || $this->isBlocked()) {
            throw new InvalidCardStateException();
        }

        return $this->recordThat(CardCompleted::of(Carbon::now()));
    }

    public function revoke(): self
    {
        if ($this->isRevoked() || $this->isCompleted()) {
            throw new InvalidCardStateException();
        }

        return $this->recordThat(CardRevoked::of(Carbon::now()));
    }

    public function block(): self
    {
        if ($this->isBlocked() || $this->isCompleted() || $this->isRevoked()) {
            throw new InvalidCardStateException();
        }

        return $this->recordThat(CardBlocked::of(Carbon::now()));
    }

    public function unblock(): self
    {
        if (!$this->isBlocked() || $this->isRevoked()) {
            throw new InvalidCardStateException();
        }

        return $this->recordThat(CardUnblocked::of());
    }

    public function noteAchievement(Achievement $achievement): self
    {
        if ($this->isSatisfied() || $this->isCompleted() || $this->isBlocked() || $this->isRevoked()) {
            throw new InvalidCardStateException();
        }

        return $this->recordThat(AchievementNoted::of($achievement))->tryToSatisfy();
    }

    public function dismissAchievement(string $achievementId): self
    {
        if ($this->isCompleted() || $this->isBlocked() || $this->isRevoked()) {
            throw new InvalidCardStateException();
        }

        return $this->recordThat(AchievementDismissed::of($achievementId))->tryToWithdrawSatisfaction();
    }

    public function acceptRequirements(Achievements $requirements): self
    {
        if ($this->isSatisfied() || $this->isCompleted() || $this->isRevoked()) {
            throw new InvalidCardStateException();
        }

        return $this->recordThat(RequirementsAccepted::of($requirements))->tryToSatisfy();
    }

    public function fixAchievementDescription(Achievement $achievement): self
    {
        return $this->recordThat(AchievementDescriptionFixed::of($achievement));
    }

    public function getDescription(): ?Description
    {
        return $this->description;
    }

    public function getAchievements(): Achievements
    {
        return $this->achievements;
    }

    public function getRequirements(): Achievements
    {
        return $this->requirements;
    }

    public function isCompleted(): bool
    {
        return $this->completed !== null;
    }

    public function isRevoked(): bool
    {
        return $this->revoked !== null;
    }

    public function isBlocked(): bool
    {
        return $this->blocked !== null;
    }

    public function isSatisfied(): bool
    {
        return $this->satisfied !== null;
    }

    public function isIssued(): bool
    {
        return $this->issued !== null;
    }

    private function tryToSatisfy(): self
    {
        $requirements = $this->requirements->filterRemaining($this->achievements);
        if ($requirements->isEmpty()) {
            return $this->recordThat(CardSatisfied::of(Carbon::now()));
        }
        return $this;
    }

    private function tryToWithdrawSatisfaction(): self
    {
        if (!$this->isSatisfied()) {
            return $this;
        }

        $requirements = $this->requirements->filterRemaining($this->achievements);
        if (!$requirements->isEmpty()) {
            return $this->recordThat(CardSatisfactionWithdrawn::of());
        }

        return $this;
    }

    private function applyAchievementNoted(AchievementNoted $achievementNoted): void
    {
        $this->achievements = $this->achievements->add($achievementNoted->achievement);
    }

    private function applyAchievementDismissed(AchievementDismissed $achievementDismissed): void
    {
        $this->achievements = $this->achievements->removeById($achievementDismissed->achievementId);
    }

    private function applyAchievementDescriptionFixed(AchievementDescriptionFixed $achievementDescriptionFixed): void
    {
        $this->achievements = $this->achievements->replace($achievementDescriptionFixed->achievement);
        $this->requirements = $this->requirements->replace($achievementDescriptionFixed->achievement);
    }
}
