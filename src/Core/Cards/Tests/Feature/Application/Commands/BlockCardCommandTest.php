<?php

namespace Cardz\Core\Cards\Tests\Feature\Application\Commands;

use Cardz\Core\Cards\Application\Commands\BlockCard;
use Cardz\Core\Cards\Domain\Events\Card\CardBlocked;
use Cardz\Core\Cards\Tests\Feature\CardsTestHelperTrait;
use Cardz\Core\Cards\Tests\Support\Builders\CardBuilder;
use Codderz\Platypus\Infrastructure\Tests\ApplicationTestTrait;
use Codderz\Platypus\Infrastructure\Tests\BaseTestCase;

class BlockCardCommandTest extends BaseTestCase
{
    use ApplicationTestTrait, CardsTestHelperTrait;

    public function test_card_can_be_blocked()
    {
        $card = CardBuilder::make()->build();
        $this->getCardRepository()->store($card);

        $this->assertFalse($card->isBlocked());

        $command = BlockCard::of($card->cardId);
        $this->commandBus()->dispatch($command);

        $card = $this->getCardRepository()->restore($command->getCardId());

        $this->assertTrue($card->isBlocked());
        $this->assertEvent(CardBlocked::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApplication();
    }

}
