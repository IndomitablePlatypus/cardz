<?php

namespace Codderz\Platypus\Infrastructure\Persistence\EventStore\Eloquent;

use App\Models\ESStorage;
use Codderz\Platypus\Contracts\Domain\AggregateEventInterface;
use Codderz\Platypus\Contracts\Domain\EventDrivenAggregateRootInterface;
use Codderz\Platypus\Contracts\Exceptions\NotFoundExceptionInterface;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Logging\SimpleLoggerTrait;
use Illuminate\Database\Eloquent\Builder;
use JsonException;

trait EloquentEventRepositoryTrait
{
    use SimpleLoggerTrait;

    public function store(EventDrivenAggregateRootInterface $aggregateRoot): array
    {
        $events = $aggregateRoot->releaseEvents();
        $data = [];
        foreach ($events as $event) {
            $data[] = $event->toArray();
        }
        $this->getEloquentStoreBuilder()->insert($data);
        return $events;
    }

    /**
     * @return AggregateEventInterface[]
     * @throws NotFoundExceptionInterface
     */
    protected function getRestoredEvents(GenericIdInterface $id): array
    {
        $esEvents = $this->getEloquentStoreBuilder()
            ->where('channel', '=', $this->getAggregateRootName())
            ->where('stream', '=', $id)
            ->orderBy('at')
            ->get();

        $this->assertAggregateRootCanBeRestored($id, $esEvents->isNotEmpty());

        $events = [];

        foreach ($esEvents as $esEvent) {
            $event = $this->restoreEvent($esEvent);
            if ($event) {
                $events[] = $event;
            }
        }

        return $events;
    }

    protected function restoreEvent(object $esEvent): ?AggregateEventInterface
    {
        /** @var AggregateEventInterface $eventClass */
        $eventClass = $esEvent->name;
        try {
            $changeset = json_decode($esEvent->changeset, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $this->error("Unable to restore $eventClass event.");
            return null;
        }

        return $eventClass::from($changeset);
    }

    protected function getEloquentStoreBuilder(): Builder
    {
        return ESStorage::query();
    }

    abstract protected function getAggregateRootName(): string;

    /**
     * @throws NotFoundExceptionInterface
     */
    abstract protected function assertAggregateRootCanBeRestored(GenericIdInterface $id, bool $eventsExist): void;
}
