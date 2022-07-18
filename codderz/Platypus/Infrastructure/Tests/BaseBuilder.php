<?php

namespace Codderz\Platypus\Infrastructure\Tests;

use Codderz\Platypus\Contracts\Tests\BuilderInterface;
use Codderz\Platypus\Infrastructure\Support\FakerTrait;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseBuilder implements BuilderInterface
{
    use FakerTrait;

    protected array $forceConstructExceptions = [
        'methodPrefix',
        'events',
    ];

    public static function make(): static
    {
        $builder = new static();
        $builder->faker();
        return $builder->generate();
    }

    protected function forceConstruct(string $class): mixed
    {
        $data = $this->getGeneratedData();

        $reflectionClass = new ReflectionClass($class);
        $reflectedObject = $reflectionClass->newInstanceWithoutConstructor();

        $filter = ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE;

        foreach ($reflectionClass->getProperties($filter) as $property) {
            if (in_array($property->getName(), $this->forceConstructExceptions, true)) {
                continue;
            }

            $property->setAccessible(true);
            $property->setValue($reflectedObject, $data[$property->getName()] ?? null);
        }

        return $reflectedObject;
    }

    protected function getGeneratedData(): array
    {
        $reflectionClass = new ReflectionClass($this);
        $ownName = $reflectionClass->getName();

        $generatedData = [];

        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->getDeclaringClass()->getName() !== $ownName) {
                continue;
            }
            $generatedData[$property->getName()] = $property->getValue($this);
        }

        return $generatedData;
    }
}
