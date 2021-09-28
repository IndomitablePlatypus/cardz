<?php

namespace App\Contexts\Personal;

use App\Contexts\Personal\Application\Contracts\PersonRepositoryInterface;
use App\Contexts\Personal\Application\Controllers\Consumers\PersonJoinedConsumer;
use App\Contexts\Personal\Application\Controllers\Consumers\RegistrationCompletedConsumer;
use App\Contexts\Personal\Infrastructure\Persistence\PersonRepository;
use App\Shared\Contracts\ReportingBusInterface;
use Illuminate\Support\ServiceProvider;

class PersonalProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PersonRepositoryInterface::class, PersonRepository::class);
    }

    public function boot(ReportingBusInterface $reportingBus)
    {
        $reportingBus->subscribe($this->app->make(PersonJoinedConsumer::class));
        $reportingBus->subscribe($this->app->make(RegistrationCompletedConsumer::class));
    }
}
