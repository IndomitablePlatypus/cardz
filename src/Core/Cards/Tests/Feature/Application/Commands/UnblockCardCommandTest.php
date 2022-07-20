<?php

namespace Cardz\Core\Cards\Tests\Feature\Application\Commands;

use Cardz\Core\Cards\Application\Commands\UnblockCard;
use Cardz\Core\Cards\Domain\Events\Card\CardBlocked;
use Cardz\Core\Cards\Domain\Events\Card\CardUnblocked;
use Cardz\Core\Cards\Tests\Feature\CardsTestHelperTrait;
use Cardz\Core\Cards\Tests\Support\Builders\CardBuilder;
use Codderz\Platypus\Infrastructure\Tests\ApplicationTestTrait;
use Codderz\Platypus\Infrastructure\Tests\BaseTestCase;

class UnblockCardCommandTest extends BaseTestCase
{
    use ApplicationTestTrait, CardsTestHelperTrait;

    public function test_card_can_be_unblocked()
    {
        $card = CardBuilder::make()->build();
        $card->block();
        $this->getCardRepository()->store($card);

        $this->assertTrue($card->isBlocked());

        $command = UnblockCard::of($card->cardId);
        $this->commandBus()->dispatch($command);

        $card = $this->getCardRepository()->restore($command->getCardId());

        $this->assertFalse($card->isBlocked());
        $this->assertEvent(CardUnblocked::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApplication();
    }

}
