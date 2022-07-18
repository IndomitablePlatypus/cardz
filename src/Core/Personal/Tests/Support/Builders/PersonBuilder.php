<?php

namespace Cardz\Core\Personal\Tests\Support\Builders;

use Carbon\Carbon;
use Cardz\Core\Personal\Domain\Model\Person\Name;
use Cardz\Core\Personal\Domain\Model\Person\Person;
use Cardz\Core\Personal\Domain\Model\Person\PersonId;
use Codderz\Platypus\Infrastructure\Tests\BaseBuilder;

final class PersonBuilder extends BaseBuilder
{
    public PersonId $personId;

    public Name $name;

    public Carbon $joined;

    public function build(): Person
    {
        return $this->forceConstruct(Person::class);
    }

    public function generate(): static
    {
        $this->personId = PersonId::make();
        $this->name = Name::of($this->faker->name());
        $this->joined = Carbon::now();
        return $this;
    }
}
