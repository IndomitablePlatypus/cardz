<?php

namespace Cardz\Core\Cards\Tests\Feature\Application\Commands;

use Cardz\Core\Cards\Application\Commands\DismissAchievement;
use Cardz\Core\Cards\Domain\Events\Card\AchievementDismissed;
use Cardz\Core\Cards\Domain\Model\Card\Achievement;
use Cardz\Core\Cards\Tests\Feature\CardsTestHelperTrait;
use Cardz\Core\Cards\Tests\Support\Builders\CardBuilder;
use Cardz\Core\Cards\Tests\Support\Builders\RequirementBuilder;
use Codderz\Platypus\Infrastructure\Tests\ApplicationTestTrait;
use Codderz\Platypus\Infrastructure\Tests\BaseTestCase;

class DismissAchievementCommandTest extends BaseTestCase
{
    use ApplicationTestTrait, CardsTestHelperTrait;

    public function test_achievement_can_be_dismissed()
    {
        $requirements = RequirementBuilder::generateSeries();
        $card = CardBuilder::make()
            ->withRequirements(...$requirements)
            ->withAchievements(Achievement::of($requirements[0]->requirementId, $requirements[0]->description))
            ->build();
        $this->getCardRepository()->store($card);

        $command = DismissAchievement::of($card->cardId, $requirements[0]->requirementId);
        $this->commandBus()->dispatch($command);

        $card = $this->getCardRepository()->restore($command->getCardId());

        $this->assertEmpty($card->getAchievements()->toArray());
        $this->assertEvent(AchievementDismissed::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApplication();
    }

}
