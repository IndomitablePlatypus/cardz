<?php

namespace Codderz\Platypus\Infrastructure\Support\Domain;

use Codderz\Platypus\Contracts\Domain\AggregateEventInterface;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Support\GuidBasedImmutableId;
use Codderz\Platypus\Infrastructure\Support\JsonArrayPresenterTrait;

trait EventDrivenAggregateRootTrait
{
    use JsonArrayPresenterTrait;

    protected string $methodPrefix = 'apply';

    /**
     * @var AggregateEventInterface[]
     */
    protected array $events = [];

    public static function fromEvents(AggregateEventInterface ...$aggregateEvents): ?static
    {
        if (!$aggregateEvents) {
            return null;
        }
        return (new static(static::idFromEventStream($aggregateEvents[0]->stream())))->apply(...$aggregateEvents);
    }

    abstract protected static function idFromEventStream(GenericIdInterface $id): mixed;

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
            $method = $this->getApplyingMethodName($aggregateEvent);
            $this->$method($aggregateEvent);
        }
        return $this;
    }

    protected function incorporateChangeset(AggregateEventInterface $aggregateEvent): void
    {
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
        return method_exists($this, $methodName) ? $methodName : 'incorporateChangeset';
    }
}
