<?php

namespace App\Contexts\Cards\Infrastructure\Persistence;

use App\Contexts\Cards\Application\Contracts\CardRepositoryInterface;
use App\Contexts\Cards\Domain\Model\Card\Achievement;
use App\Contexts\Cards\Domain\Model\Card\Card;
use App\Contexts\Cards\Domain\Model\Card\CardId;
use App\Contexts\Cards\Domain\Model\Card\RequirementId;
use App\Models\Card as EloquentCard;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use function json_try_decode;
use function json_try_encode;

class CardRepository implements CardRepositoryInterface
{
    public function persist(?Card $card = null): void
    {
        if ($card === null) {
            return;
        }

        EloquentCard::query()->updateOrCreate(
            ['id' => $card->cardId],
            $this->cardAsData($card)
        );
    }

    private function cardAsData(Card $card): array
    {
        $reflection = new ReflectionClass($card);
        $properties = [
            'issued' => null,
            'satisfied' => null,
            'completed' => null,
            'revoked' => null,
            'blocked' => null,
        ];

        foreach ($properties as $key => $property) {
            $property = $reflection->getProperty($key);
            $property->setAccessible(true);
            $properties[$key] = $property->getValue($card);
        }

        $data = [
            'id' => (string) $card->cardId,
            'plan_id' => (string) $card->planId,
            'customer_id' => (string) $card->customerId,
            'description' => (string) $card->getDescription(),
            'issued_at' => $properties['issued'],
            'satisfied_at' => $properties['satisfied'],
            'completed_at' => $properties['completed'],
            'revoked_at' => $properties['revoked'],
            'blocked_at' => $properties['blocked'],
            'achievements' => json_try_encode($this->achievementsAsData($card->getAchievements())),
        ];

        return $data;
    }

    /**
     * @param array<Achievement> $achievements
     */
    #[Pure] private function achievementsAsData(array $achievements): array
    {
        $achievementsData = [];
        /** @var Achievement $achievement */
        foreach ($achievements as $achievement) {
            $achievementsData[] = $achievement->toArray();
        }
        return $achievementsData;
    }

    public function take(CardId $cardId = null): ?Card
    {
        /** @var EloquentCard $eloquentCard */
        $eloquentCard = EloquentCard::query()->where([
            'id' => (string) $cardId,
            'blocked_at' => null,
        ])?->first();
        if ($eloquentCard === null) {
            return null;
        }
        return $this->cardFromData($eloquentCard);
    }

    private function cardFromData(EloquentCard $eloquentCard): Card
    {
        $reflection = new ReflectionClass(Card::class);
        $creator = $reflection->getMethod('from');
        $creator?->setAccessible(true);
        /** @var Card $card */
        $card = $reflection->newInstanceWithoutConstructor();

        $creator?->invoke($card,
            $eloquentCard->id,
            $eloquentCard->plan_id,
            $eloquentCard->customer_id,
            $eloquentCard->description,
            $eloquentCard->issued_at,
            $eloquentCard->satisfied_at,
            $eloquentCard->completed_at,
            $eloquentCard->revoked_at,
            $eloquentCard->blocked_at,
            $this->achievementsFromData($eloquentCard->achievements)
        );
        return $card;
    }

    /**
     * @return Achievement[]|null
     */
    private function achievementsFromData($achievementsData): ?array
    {
        if (is_string($achievementsData)) {
            $achievementsData = json_try_decode($achievementsData);
        }
        if (!is_array($achievementsData)) {
            return [];
        }

        $achievements = [];
        foreach ($achievementsData as $achievementData) {
            $id = $achievementData->requirementId;
            $achievements[$id] = Achievement::of(RequirementId::of($id), $achievementData->description);
        }
        return $achievements;
    }
}
