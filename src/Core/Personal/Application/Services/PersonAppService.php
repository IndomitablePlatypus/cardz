<?php

namespace Cardz\Core\Personal\Application\Services;

use Cardz\Core\Personal\Application\Commands\ChangePersonName;
use Cardz\Core\Personal\Application\Commands\JoinPerson;
use Cardz\Core\Personal\Domain\Model\Person\Person;
use Cardz\Core\Personal\Domain\Model\Person\PersonId;
use Cardz\Core\Personal\Domain\Persistence\Contracts\PersonStoreInterface;
use Cardz\Core\Personal\Infrastructure\Messaging\DomainEventBusInterface;

class PersonAppService
{
    public function __construct(
        private DomainEventBusInterface $domainEventBus,
        private PersonStoreInterface $personStore,
    ) {
    }

    public function join(JoinPerson $command): PersonId
    {
        $person = Person::join($command->getPersonId(), $command->getName());
        $this->release($person);
        return $person->personId;
    }

    public function changeName(ChangePersonName $command): PersonId
    {
        $person = $this->personStore->restore($command->getPersonId());
        $person->changeName($command->getName());
        $this->release($person);
        return $person->personId;
    }

    private function release(Person $person)
    {
        $events = $this->personStore->store($person);
        $this->domainEventBus->publish(...$events);
    }
}
