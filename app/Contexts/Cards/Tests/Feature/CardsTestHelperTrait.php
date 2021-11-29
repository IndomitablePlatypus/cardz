<?php

namespace App\Contexts\Cards\Tests\Feature;

use App\Contexts\Cards\Domain\Persistence\Contracts\CardRepositoryInterface;
use App\Contexts\Cards\Domain\Persistence\Contracts\PlanRepositoryInterface;
use App\Contexts\Cards\Tests\Support\Mocks\CardInMemoryRepository;
use App\Contexts\Cards\Tests\Support\Mocks\PlanInMemoryRepository;

trait CardsTestHelperTrait
{
    protected function setupApplication(): void
    {
        $this->app->singleton(CardRepositoryInterface::class, CardInMemoryRepository::class);
        $this->app->singleton(PlanRepositoryInterface::class, PlanInMemoryRepository::class);
    }

    protected function getCardRepository(): CardRepositoryInterface
    {
        return $this->app->make(CardRepositoryInterface::class);
    }

    protected function getPlanRepository(): PlanRepositoryInterface
    {
        return $this->app->make(PlanRepositoryInterface::class);
    }
}
