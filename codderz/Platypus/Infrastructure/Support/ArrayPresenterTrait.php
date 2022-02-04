<?php

namespace Codderz\Platypus\Infrastructure\Support;

use Carbon\Carbon;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;
use Stringable;

trait ArrayPresenterTrait
{
    public function toArray(bool $snakify = false, bool $publicOnly = false, bool $ownOnly = false, array $except = []): array
    {
        $filter = $publicOnly ? ReflectionProperty::IS_PUBLIC : null;
        $array = [];

        $reflectionClass = new ReflectionClass($this);
        $ownName =  $reflectionClass->getName();
        foreach ($reflectionClass->getProperties($filter) as $property) {
            $property->setAccessible(true);
            if ($ownOnly && $property->getDeclaringClass()->getName() !== $ownName) {
                continue;
            }
            if (in_array($property->getName(), $except)) {
                continue;
            }
            $value = $property->hasType() && $property->isInitialized($this) ? $this->_toArrayNestedValue($property->getValue($this)) : null;
            $array[$this->transformName($property->getName(), $snakify)] = $value;
        }

        return $array;
    }

    protected function _toArrayNestedValue($value): mixed
    {
        return match (true) {
            $value instanceof Carbon => $value,
            $value instanceof Stringable => (string) $value,
            is_object($value) && method_exists($value, 'toArray') => $value->toArray(),
            default => $value,
        };
    }

    protected function transformName(string $name, bool $snakify): string
    {
        return $snakify ? Str::snake($name) : Str::camel($name);
    }
}
