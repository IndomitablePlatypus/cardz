<?php

namespace Cardz\Core\Cards\Tests\Feature\Application\Commands;

use Cardz\Core\Cards\Application\Commands\AcceptRequirements;
use Cardz\Core\Cards\Domain\Events\Card\RequirementsAccepted;
use Cardz\Core\Cards\Tests\Feature\CardsTestHelperTrait;
use Cardz\Core\Cards\Tests\Support\Builders\CardBuilder;
use Cardz\Core\Cards\Tests\Support\Builders\RequirementBuilder;
use Codderz\Platypus\Infrastructure\Tests\ApplicationTestTrait;
use Codderz\Platypus\Infrastructure\Tests\BaseTestCase;

class AcceptRequirementsCommandTest extends BaseTestCase
{
    use ApplicationTestTrait, CardsTestHelperTrait;

    public function test_card_accepts_requirements()
    {
        $requirements = RequirementBuilder::generateSeries(10);
        $card = CardBuilder::make()->build();
        $this->getCardRepository()->store($card);

        $command = AcceptRequirements::of($card->cardId, ...$requirements);
        $this->commandBus()->dispatch($command);

        $card = $this->getCardRepository()->restore($command->getCardId());

        $this->assertEquals($command->getRequirements(), $card->getRequirements());
        $this->assertEvent(RequirementsAccepted::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApplication();
    }

}
