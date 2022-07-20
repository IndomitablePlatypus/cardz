<?php

namespace Cardz\Core\Cards\Infrastructure\ReadStorage\Eloquent;

use App\Models\Card as EloquentCard;
use Carbon\Carbon;
use Cardz\Core\Cards\Domain\Model\Card\Achievements;
use Cardz\Core\Cards\Domain\ReadModel\ReadCard;
use Cardz\Core\Cards\Infrastructure\ReadStorage\Contracts\CardReadStorageInterface;
use JetBrains\PhpStorm\ArrayShape;

class CardReadStorage implements CardReadStorageInterface
{
    public function persist(ReadCard $card): void
    {
        EloquentCard::query()->updateOrCreate(
            ['id' => $card->cardId],
            $this->cardAsData($card)
        );
    }

    public function take(?string $cardId): ?ReadCard
    {
        if ($cardId === null) {
            return null;
        }
        /** @var EloquentCard $eloquentCard */
        $eloquentCard = EloquentCard::query()->find($cardId);
        if ($eloquentCard === null) {
            return null;
        }
        return $this->readCardFromData($eloquentCard);
    }

    #[ArrayShape([
        'id' => "string",
        'plan_id' => "string",
        'customer_id' => "string",
        'issued_at' => Carbon::class,
        'satisfied_at' => Carbon::class,
        'completed_at' => Carbon::class,
        'revoked_at' => Carbon::class,
        'blocked_at' => Carbon::class,
        'achievements' => Achievements::class,
        'requirements' => Achievements::class,
    ])]
    private function cardAsData(ReadCard $readCard): array
    {
        return [
            'id' => $readCard->cardId,
            'plan_id' => $readCard->planId,
            'customer_id' => $readCard->customerId,
            'issued_at' => $readCard->issued,
            'satisfied_at' => $readCard->satisfied,
            'completed_at' => $readCard->completed,
            'revoked_at' => $readCard->revoked,
            'blocked_at' => $readCard->blocked,
            'achievements' => $readCard->achievements->toArray(),
            'requirements' => $readCard->requirements->toArray(),
        ];
    }

    private function readCardFromData(EloquentCard $card): ReadCard
    {
        $achievements = is_string($card->achievements) ? json_try_decode($card->achievements, true) : $card->achievements;
        $requirements = is_string($card->requirements) ? json_try_decode($card->requirements, true) : $card->requirements;

        return new ReadCard(
            $card->id,
            $card->plan_id,
            $card->customer_id,
            $card->issued_at ? new Carbon($card->issued_at) : null,
            $card->satisfied_at ? new Carbon($card->satisfied_at) : null,
            $card->completed_at ? new Carbon($card->completed_at) : null,
            $card->revoked_at ? new Carbon($card->revoked_at) : null,
            $card->blocked_at ? new Carbon($card->blocked_at) : null,
            Achievements::of($achievements),
            Achievements::of($requirements),
        );
    }

}
