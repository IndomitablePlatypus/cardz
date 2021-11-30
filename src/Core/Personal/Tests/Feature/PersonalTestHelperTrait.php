<?php

namespace Cardz\Core\Personal\Tests\Feature;

use Cardz\Core\Personal\Domain\Persistence\Contracts\PersonRepositoryInterface;
use Cardz\Core\Personal\Tests\Support\Mocks\PersonInMemoryRepository;

trait PersonalTestHelperTrait
{
    protected function setupApplication(): void
    {
        $this->app->singleton(PersonRepositoryInterface::class, PersonInMemoryRepository::class);
    }

    protected function getPersonRepository(): PersonRepositoryInterface
    {
        return $this->app->make(PersonRepositoryInterface::class);
    }
}
