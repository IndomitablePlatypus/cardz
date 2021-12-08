<?php

namespace Cardz\Core\Personal\Infrastructure\Persistence\Eloquent;

use App\Models\ESStorage;
use Cardz\Core\Personal\Domain\Exception\PersonNotFoundExceptionInterface;
use Cardz\Core\Personal\Domain\Model\Person\Person;
use Cardz\Core\Personal\Domain\Model\Person\PersonId;
use Cardz\Core\Personal\Domain\Persistence\Contracts\PersonRepositoryInterface;
use Cardz\Core\Personal\Infrastructure\Exceptions\PersonNotFoundException;
use Codderz\Platypus\Contracts\Domain\AggregateEventInterface;

class PersonRepository implements PersonRepositoryInterface
{
    protected const CONTEXT = 'personal';
    protected const CHANNEL = 'person';
    protected const VERSION = 1;
    protected const EVENT_NAMESPACE = 'Cardz\\Core\\Personal\\Domain\\Events\\Person\\';

    public function store(Person $person): array
    {
        $events = $person->releaseEvents();
        $data = [];
        foreach ($events as $event) {
            $data[] = $event->toArray();
        }
        ESStorage::query()->insert($data);
        return $events;
    }

    /**
     * @throws PersonNotFoundExceptionInterface
     */
    public function restore(PersonId $personId): Person
    {
        $esEvents = $this->getESEvents($personId);

        $events = [];
        foreach ($esEvents as $esEvent) {
            $events[] = $this->restoreEvent($esEvent);
        }
        return (new Person($personId))->apply(...$events);
    }

    /**
     * @return ESStorage[]
     * @throws PersonNotFoundExceptionInterface
     */
    protected function getESEvents(string $personId): array
    {
        $esEvents = ESStorage::query()
            ->where('context', '=', $this::CONTEXT)
            ->where('channel', '=', $this::CHANNEL)
            ->where('stream', '=', $personId)
            ->orderBy('recorded_at')
            ->get();
        if ($esEvents->isEmpty()) {
            throw new PersonNotFoundException("Person $personId not found");
        }
        return $esEvents->all();
    }

    protected function restoreEvent(ESStorage $esEvent): AggregateEventInterface
    {
        $eventClass = $this::EVENT_NAMESPACE . $esEvent->name;
        $changeset = json_decode($esEvent->changeset, true);
        return [$eventClass, 'from']($changeset);
    }
}
