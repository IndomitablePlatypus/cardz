<?php

namespace Cardz\Core\Cards\Tests\Feature\Application\Commands;

use Cardz\Core\Cards\Application\Commands\NoteAchievement;
use Cardz\Core\Cards\Domain\Events\Card\AchievementNoted;
use Cardz\Core\Cards\Domain\Model\Card\Achievements;
use Cardz\Core\Cards\Tests\Feature\CardsTestHelperTrait;
use Cardz\Core\Cards\Tests\Support\Builders\CardBuilder;
use Cardz\Core\Cards\Tests\Support\Builders\RequirementBuilder;
use Codderz\Platypus\Infrastructure\Tests\ApplicationTestTrait;
use Codderz\Platypus\Infrastructure\Tests\BaseTestCase;

class NoteAchievementCommandTest extends BaseTestCase
{
    use ApplicationTestTrait, CardsTestHelperTrait;

    public function test_achievement_can_be_noted()
    {
        $requirements = RequirementBuilder::generateSeries();
        $card = CardBuilder::make()->withRequirements(...$requirements)->build();
        $this->getCardRepository()->store($card);

        $command = NoteAchievement::of($card->cardId, $requirements[0]->requirementId, $requirements[0]->description);
        $this->commandBus()->dispatch($command);

        $card = $this->getCardRepository()->restore($command->getCardId());

        $requirements = Achievements::from(...$requirements);
        $this->assertEquals($requirements->toArray()[0], $card->getAchievements()->toArray()[0]);
        $this->assertEvent(AchievementNoted::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApplication();
    }

}
