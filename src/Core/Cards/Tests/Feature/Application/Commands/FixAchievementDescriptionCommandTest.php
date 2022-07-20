<?php

namespace Cardz\Core\Cards\Tests\Feature\Application\Commands;

use App\Models\ESStorage;
use Cardz\Core\Cards\Application\Commands\FixAchievementDescription;
use Cardz\Core\Cards\Domain\Events\Card\AchievementDescriptionFixed;
use Cardz\Core\Cards\Domain\Model\Card\Achievement;
use Cardz\Core\Cards\Domain\Persistence\Contracts\CardRepositoryInterface;
use Cardz\Core\Cards\Tests\Feature\CardsTestHelperTrait;
use Cardz\Core\Cards\Tests\Support\Builders\CardBuilder;
use Cardz\Core\Cards\Tests\Support\Builders\RequirementBuilder;
use Codderz\Platypus\Infrastructure\Tests\ApplicationTestTrait;
use Codderz\Platypus\Infrastructure\Tests\BaseTestCase;

class FixAchievementDescriptionCommandTest extends BaseTestCase
{
    use ApplicationTestTrait, CardsTestHelperTrait;

    public function test_achievement_can_be_fixed()
    {
        $requirements = RequirementBuilder::generateSeries();
        $card = CardBuilder::make()
            ->withRequirements(...$requirements)
            ->withAchievements(Achievement::of($requirements[0]->requirementId, $requirements[0]->description))
            ->build();
        $this->getCardRepository()->store($card);

        $command = FixAchievementDescription::of($card->cardId, $requirements[0]->requirementId, 'Changed');
        $this->commandBus()->dispatch($command);

        $card = $this->getCardRepository()->restore($command->getCardId());

        $this->assertEquals('Changed', $card->getAchievements()->toArray()[0][1]);
        $this->assertEvent(AchievementDescriptionFixed::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApplication();
    }

}
