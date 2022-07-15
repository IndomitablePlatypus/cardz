<?php

namespace Cardz\Core\Personal\Infrastructure\Persistence\Eloquent;

use Cardz\Core\Personal\Domain\Exception\PersonNotFoundExceptionInterface;
use Cardz\Core\Personal\Domain\Model\Person\Person;
use Cardz\Core\Personal\Domain\Model\Person\PersonId;
use Cardz\Core\Personal\Domain\Persistence\Contracts\PersonRepositoryInterface;
use Cardz\Core\Personal\Infrastructure\Exceptions\PersonNotFoundException;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Persistence\EventStore\Eloquent\EloquentEventRepositoryTrait;

class PersonRepository implements PersonRepositoryInterface
{
    use EloquentEventRepositoryTrait;

    /**
     * @throws PersonNotFoundExceptionInterface
     */
    public function restore(PersonId $personId): Person
    {
        return (new Person($personId))->apply(...$this->getRestoredEvents($personId));
    }

    protected function getAggregateRootName(): string
    {
        return Person::class;
    }

    protected function assertAggregateRootCanBeRestored(GenericIdInterface $id, bool $eventsExist): void
    {
        if (!$eventsExist) {
            throw new PersonNotFoundException("Person $id not found");
        }
    }
}
