<?php

namespace Codderz\Platypus\Infrastructure\Support\Domain;

use Codderz\Platypus\Contracts\Domain\AggregateEventInterface;
use Codderz\Platypus\Infrastructure\Support\JsonArrayPresenterTrait;

trait EventDrivenAggregateRootTrait
{
    use JsonArrayPresenterTrait;

    protected string $methodPrefix = 'apply';

    /**
     * @var AggregateEventInterface[]
     */
    protected array $events = [];

    /**
     * @return AggregateEventInterface[]
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    /**
     * @return AggregateEventInterface[]
     */
    public function tapEvents(): array
    {
        return $this->events;
    }

    public function recordThat(AggregateEventInterface ...$aggregateEvents): static
    {
        $this->events = [...$this->events, ...$aggregateEvents];
        $this->apply(...$aggregateEvents);
        return $this;
    }

    public function apply(AggregateEventInterface ...$aggregateEvents): static
    {
        foreach ($aggregateEvents as $aggregateEvent) {
            $aggregateEvent->in($this);
            $this->applyEvent($aggregateEvent);
        }
        return $this;
    }

    protected function applyEvent(AggregateEventInterface $aggregateEvent): void
    {
        $method = $this->getApplyingMethodName($aggregateEvent);
        if ($method) {
            $this->$method($aggregateEvent);
            return;
        }

        $properties = $aggregateEvent->changeset();
        foreach ($properties as $propertyName => $propertyValue) {
            if (property_exists($this, $propertyName)) {
                $this->$propertyName = $propertyValue;
            }
        }
    }

    protected function getApplyingMethodName(AggregateEventInterface $aggregateEvent): ?string
    {
        $methodName = $this->methodPrefix . $aggregateEvent::shortName();
        return method_exists($this, $methodName) ? $methodName : null;
    }
}
