<?php

namespace Cardz\Core\Cards\Tests\Feature\Application\Commands;

use Cardz\Core\Cards\Application\Commands\RevokeCard;
use Cardz\Core\Cards\Domain\Events\Card\CardRevoked;
use Cardz\Core\Cards\Tests\Feature\CardsTestHelperTrait;
use Cardz\Core\Cards\Tests\Support\Builders\CardBuilder;
use Codderz\Platypus\Infrastructure\Tests\ApplicationTestTrait;
use Codderz\Platypus\Infrastructure\Tests\BaseTestCase;

class RevokeCardCommandTest extends BaseTestCase
{
    use ApplicationTestTrait, CardsTestHelperTrait;

    public function test_card_can_be_revoked()
    {
        $card = CardBuilder::make()->build();
        $this->getCardRepository()->store($card);

        $this->assertFalse($card->isRevoked());

        $command = RevokeCard::of($card->cardId);
        $this->commandBus()->dispatch($command);

        $card = $this->getCardRepository()->restore($command->getCardId());

        $this->assertTrue($card->isRevoked());
        $this->assertEvent(CardRevoked::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApplication();
    }

}
