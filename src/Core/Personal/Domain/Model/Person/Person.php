<?php

namespace Cardz\Core\Personal\Domain\Model\Person;

use Carbon\Carbon;
use Cardz\Core\Personal\Domain\Events\Person\PersonJoined;
use Cardz\Core\Personal\Domain\Events\Person\PersonNameChanged;
use Codderz\Platypus\Contracts\Domain\EventDrivenAggregateRootInterface;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Support\Domain\EventDrivenAggregateRootTrait;

final class Person implements EventDrivenAggregateRootInterface
{
    use EventDrivenAggregateRootTrait;

    public Name $name;

    private ?Carbon $joined = null;

    protected static function idFromEventStream(GenericIdInterface $id): PersonId
    {
        return PersonId::of($id);
    }

    public function __construct(
        public PersonId $personId,
    ) {
    }

    public static function join(PersonId $personId, Name $name): self
    {
        return (new self($personId))->recordThat(PersonJoined::of($name, Carbon::now()));
    }

    public function id(): PersonId
    {
        return $this->personId;
    }

    public function changeName(Name $name): self
    {
        return $this->recordThat(PersonNameChanged::of($name));
    }

    public function isJoined(): bool
    {
        return $this->joined !== null;
    }
}
