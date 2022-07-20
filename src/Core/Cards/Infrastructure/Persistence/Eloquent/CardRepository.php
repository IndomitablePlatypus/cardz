<?php

namespace Cardz\Core\Cards\Infrastructure\Persistence\Eloquent;

use Cardz\Core\Cards\Domain\Exceptions\CardNotFoundExceptionInterface;
use Cardz\Core\Cards\Domain\Model\Card\Card;
use Cardz\Core\Cards\Domain\Model\Card\CardId;
use Cardz\Core\Cards\Domain\Persistence\Contracts\CardRepositoryInterface;
use Cardz\Core\Cards\Infrastructure\Exceptions\CardNotFoundException;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Persistence\EventStore\Eloquent\EloquentEventRepositoryTrait;

class CardRepository implements CardRepositoryInterface
{
    use EloquentEventRepositoryTrait;

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function restore(CardId $cardId): Card
    {
        return Card::draft($cardId)->apply(...$this->getRestoredEvents($cardId));
    }

    protected function getAggregateRootName(): string
    {
        return Card::class;
    }

    protected function assertAggregateRootCanBeRestored(GenericIdInterface $id, bool $eventsExist): void
    {
        if (!$eventsExist) {
            throw new CardNotFoundException("Card $id not found");
        }
    }
}
