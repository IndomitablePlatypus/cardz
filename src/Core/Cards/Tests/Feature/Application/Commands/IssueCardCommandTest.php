<?php

namespace Cardz\Core\Cards\Tests\Feature\Application\Commands;

use Cardz\Core\Cards\Application\Commands\IssueCard;
use Cardz\Core\Cards\Domain\Events\Card\CardIssued;
use Cardz\Core\Cards\Domain\Model\Card\CustomerId;
use Cardz\Core\Cards\Domain\Model\Plan\PlanId;
use Cardz\Core\Cards\Tests\Feature\CardsTestHelperTrait;
use Codderz\Platypus\Infrastructure\Tests\ApplicationTestTrait;
use Codderz\Platypus\Infrastructure\Tests\BaseTestCase;

class IssueCardCommandTest extends BaseTestCase
{
    use ApplicationTestTrait, CardsTestHelperTrait;

    public function test_card_can_be_issued()
    {
        $command = IssueCard::of(PlanId::makeValue(), CustomerId::makeValue());
        $this->commandBus()->dispatch($command);

        $card = $this->getCardRepository()->restore($command->getCardId());

        $this->assertEquals($command->getCardId(), $card->cardId);
        $this->assertEvent(CardIssued::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApplication();
    }

}
