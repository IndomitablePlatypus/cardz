<?php

namespace App\Contexts\Cards\Application\IntegrationEvents;

class AchievementDismissed implements CardsReportable
{
    public function __toString(): string
    {
        return self::class;
    }

}
