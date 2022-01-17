<?php

namespace Cardz\Generic\Authorization\Domain\Attribute;

use Codderz\Platypus\Contracts\Authorization\Abac\AttributeCollectionInterface;
use Codderz\Platypus\Contracts\Authorization\Abac\AttributeInterface;
use Codderz\Platypus\Exceptions\AuthorizationFailedException;
use JetBrains\PhpStorm\Pure;

final class Attributes implements AttributeCollectionInterface
{
    /** @var Attribute[] */
    private array $attributes = [];

    #[Pure]
    public function __construct(Attribute ...$attributes)
    {
        foreach ($attributes as $attribute) {
            $this->attributes[$attribute->name()] = $attribute;
        }
    }

    #[Pure]
    public static function of(array $attributeItems = []): self
    {
        $collection = new self();
        foreach ($attributeItems as $name => $value) {
            $collection->attributes[$name] = Attribute::of($name, $value);
        }
        return $collection;
    }

    #[Pure]
    public function toArray(): array
    {
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[$attribute->name()] = $attribute->value();
        }
        return $attributes;
    }

    public function attr(string $attributeName): AttributeInterface
    {
        $attribute = $this->attributes[$attributeName] ?? null;
        if ($attribute === null) {
            throw new AuthorizationFailedException("Attribute $attributeName not found");
        }
        return $attribute;
    }

    #[Pure]
    public function getValue(string $attributeName)
    {
        return ($this->attributes[$attributeName] ?? null)?->value();
    }

    public function count(): int
    {
        return count($this->attributes);
    }

    public function __invoke(string $attributeName): Attribute
    {
        return $this->attr($attributeName);
    }
}
