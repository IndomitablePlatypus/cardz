<?php

namespace Cardz\Core\Cards\Domain\Persistence\Contracts;

use Cardz\Core\Cards\Domain\Exceptions\CardNotFoundExceptionInterface;
use Cardz\Core\Cards\Domain\Model\Card\Card;
use Cardz\Core\Cards\Domain\Model\Card\CardId;
use Codderz\Platypus\Contracts\Domain\AggregateEventInterface;

interface CardRepositoryInterface
{
    /**
     * @return AggregateEventInterface[]
     */
    public function store(Card $card): array;

    /**
     * @throws CardNotFoundExceptionInterface
     */
    public function restore(CardId $cardId): Card;
}
