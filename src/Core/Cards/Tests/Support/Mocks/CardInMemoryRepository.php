<?php

namespace Cardz\Core\Cards\Tests\Support\Mocks;

use Cardz\Core\Cards\Domain\Model\Card\Card;
use Cardz\Core\Cards\Domain\Model\Card\CardId;
use Cardz\Core\Cards\Domain\Persistence\Contracts\CardRepositoryInterface;

class CardInMemoryRepository implements CardRepositoryInterface
{
    protected static array $events = [];

    public function store(Card $card): array
    {
        $id = (string) $card->cardId;
        $events = $card->releaseEvents();
        static::$events[$id] ??= [];
        static::$events[$id] = [...static::$events[$id], ...$events];
        return $events;
    }

    public function restore(CardId $cardId): Card
    {
        $events = collect(static::$events[(string) $cardId] ??= [])->sortByDesc(function ($event, $key) {
            return $event->at()->timestamp;
        });
        return Card::draft($cardId)->apply(...$events->all());
    }
}
