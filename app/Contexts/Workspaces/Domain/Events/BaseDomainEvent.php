<?php

namespace App\Contexts\Workspaces\Domain\Events;

use Carbon\Carbon;
use ReflectionClass;
use Throwable;

abstract class BaseDomainEvent
{
    private Carbon $on;

    protected function __construct()
    {
        $this->on = Carbon::now();
    }

    public function __toString(): string
    {
        try {
            return json_try_encode($this->toArray());
        } catch (Throwable $exception) {
            return '';
        }
    }

    private function toArray(): array
    {
        $reflectionClass = new ReflectionClass($this);
        $array = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if ($property->getValue($this) !== null) {
                $array[$property->getName()] = $property->getValue($this);
            }
        }
        return $array;
    }

    public function on(): Carbon
    {
        return $this->on;
    }
}
