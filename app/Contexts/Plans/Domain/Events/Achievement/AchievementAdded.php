<?php

namespace App\Contexts\Plans\Domain\Events\Achievement;

use App\Contexts\Plans\Domain\Model\Achievement\AchievementId;

class AchievementAdded extends BaseAchievementDomainEvent
{
    public static function with(AchievementId $achievementId): static
    {
        return new static($achievementId);
    }
}
