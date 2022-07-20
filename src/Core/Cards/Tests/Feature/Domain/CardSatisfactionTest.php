<?php

namespace Cardz\Core\Cards\Tests\Feature\Domain;

use Carbon\Carbon;
use Cardz\Core\Cards\Domain\Events\Card\CardSatisfactionWithdrawn;
use Cardz\Core\Cards\Domain\Events\Card\CardSatisfied;
use Cardz\Core\Cards\Domain\Model\Card\Achievement;
use Cardz\Core\Cards\Domain\Model\Card\CardId;
use Cardz\Core\Cards\Domain\Model\Card\CustomerId;
use Cardz\Core\Cards\Tests\Feature\CardsTestHelperTrait;
use Cardz\Core\Cards\Tests\Support\Builders\CardBuilder;
use Cardz\Core\Cards\Tests\Support\Builders\PlanBuilder;
use Cardz\Core\Cards\Tests\Support\Builders\RequirementBuilder;
use Codderz\Platypus\Infrastructure\Tests\ApplicationTestTrait;
use Codderz\Platypus\Infrastructure\Tests\BaseTestCase;

class CardSatisfactionTest extends BaseTestCase
{
    use ApplicationTestTrait, CardsTestHelperTrait;

    public function test_issued_card_on_empty_plan_is_satisfied()
    {
        $planBuilder = PlanBuilder::make();
        $planBuilder->requirements = [];

        $cardId = CardId::make();
        $card = $planBuilder->build()->issueCard($cardId, CustomerId::make());
        $this->assertTrue($card->isSatisfied());
    }

    public function test_issued_card_is_satisfied_on_requirements_completed()
    {
        $requirement = RequirementBuilder::make()->build();
        $planBuilder = PlanBuilder::make();
        $planBuilder->requirements = [$requirement];

        $cardId = CardId::make();
        $card = $planBuilder->build()->issueCard($cardId, CustomerId::make());
        $this->assertFalse($card->isSatisfied());

        $card->noteAchievement(Achievement::of($requirement->requirementId, $requirement->description));
        $this->assertTrue($card->isSatisfied());
    }

    public function test_satisfaction_is_withdrawn_on_dismissing_achievement()
    {
        $requirement = RequirementBuilder::make()->build();
        $achievement = Achievement::of($requirement->requirementId, $requirement->description);
        $cardBuilder = CardBuilder::make()
            ->withRequirements($requirement)
            ->withAchievements($achievement);
        $cardBuilder->satisfied = Carbon::now();
        $card = $cardBuilder->build();

        $card->dismissAchievement($requirement->requirementId);
        $this->assertFalse($card->isSatisfied());
    }

    public function setUp(): void
    {
        parent::setUp();
    }
}
