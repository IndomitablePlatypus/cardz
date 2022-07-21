<?php

namespace Codderz\Platypus\Infrastructure\Support\Domain;

use Carbon\Carbon;
use Codderz\Platypus\Contracts\Domain\EventDrivenAggregateRootInterface;
use Codderz\Platypus\Contracts\GenericIdInterface;
use Codderz\Platypus\Infrastructure\Support\ArrayPresenterTrait;
use Codderz\Platypus\Infrastructure\Support\GuidBasedImmutableId;
use Codderz\Platypus\Infrastructure\Support\JsonArrayPresenterTrait;
use Codderz\Platypus\Infrastructure\Support\ShortClassNameTrait;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonException;
use ReflectionClass;
use ReflectionProperty;

trait AggregateEventTrait
{
    use ShortClassNameTrait, JsonArrayPresenterTrait {
        JsonArrayPresenterTrait::toArray as _toArray;
    }

    protected Carbon $at;

    protected EventDrivenAggregateRootInterface $aggregateRoot;

    protected GenericIdInterface $stream;

    protected string $channel;

    public static function fromArray(array $data): ?static
    {
        try {
            $changeset = json_decode($data['changeset'], true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }

        $event = static::from($changeset);

        $event->channel = $data['channel'];
        $event->stream = GuidBasedImmutableId::of($data['stream']);
        $event->at = new Carbon($data['at']);
        $event->version = $data['version'];

        return $event;
    }

    abstract protected static function from(array $changeset): static;

    abstract public function version(): int;

    public function channel(): string
    {
        return $this->channel;
    }

    public function name(): string
    {
        return $this::class;
    }

    public function stream(): GenericIdInterface
    {
        return $this->stream;
    }

    public function at(): Carbon
    {
        return $this->at;
    }

    public function changeset(): array
    {
        $changeset = [];
        $class = new ReflectionClass($this);
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property) {
            $changeset[$property->getName()] = $property->getValue($this);
        }
        return $changeset;
    }

    public function in(EventDrivenAggregateRootInterface $aggregateRoot): static
    {
        $this->at ??= Carbon::now();
        $this->aggregateRoot = $aggregateRoot;
        $this->stream = $aggregateRoot->id();
        $this->channel = $aggregateRoot::class;
        return $this;
    }

    public function with(): EventDrivenAggregateRootInterface
    {
        return $this->aggregateRoot;
    }

    /**
     * @throws JsonException
     */
    #[ArrayShape(['channel' => "string", 'name' => "string", 'stream' => "string", 'at' => Carbon::class, 'version' => "int", 'changeset' => "string"])]
    public function toArray(): array
    {
        return [
            'channel' => $this->channel(),
            'name' => $this->name(),
            'stream' => (string) $this->stream(),
            'at' => $this->at(),
            'version' => $this->version(),
            'changeset' => json_encode($this->_toArray(publicOnly: true), JSON_THROW_ON_ERROR),
        ];
    }

    #[Pure]
    public function __toString(): string
    {
        return $this->name();
    }

    /**
     * @throws JsonException
     */
    #[ArrayShape(['channel' => "string", 'name' => "string", 'stream' => "string", 'at' => Carbon::class, 'version' => "int", 'changeset' => "string"])]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
