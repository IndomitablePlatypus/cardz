<?php

namespace App\Contexts\Cards\Infrastructure\Persistence;

use App\Contexts\Cards\Domain\Model\BlockedCard\BlockedCard;
use App\Contexts\Cards\Domain\Model\BlockedCard\BlockedCardId;
use App\Contexts\Cards\Infrastructure\Persistence\Contracts\BlockedCardRepositoryInterface;
use App\Models\Card as EloquentCard;

class BlockedCardRepository implements BlockedCardRepositoryInterface
{
    public function persist(?BlockedCard $blockedCard = null): void
    {
        if ($blockedCard === null) {
            return;
        }

        $eloquentCard = EloquentCard::query()->find((string) $blockedCard->blockedCardId);
        if ($eloquentCard === null) {
            return;
        }
        $eloquentCard->blocked_at = $blockedCard->blocked;
        $eloquentCard->save();
    }

    public function take(BlockedCardId $blockedCardId): ?BlockedCard
    {
        if ($blockedCardId === null) {
            return null;
        }
        /** @var EloquentCard $eloquentCard */
        $eloquentCard = EloquentCard::query()
            ->where('id', '=', (string) $blockedCardId)
            ->whereNotNull('blocked_at')
            ->first();
        if ($eloquentCard === null) {
            return null;
        }
        return $this->cardFromData($eloquentCard);
    }

    private function cardFromData(EloquentCard $eloquentCard): BlockedCard
    {
        $blockedCard = new BlockedCard(BlockedCardId::of($eloquentCard->id), $eloquentCard->blocked_at);
        return $blockedCard;
    }
}
