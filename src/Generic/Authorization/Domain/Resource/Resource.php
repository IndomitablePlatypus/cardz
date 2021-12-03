<?php

namespace Cardz\Generic\Authorization\Domain\Resource;

use Cardz\Generic\Authorization\Domain\Attribute\Attribute;
use Cardz\Generic\Authorization\Domain\Attribute\Attributes;
use Codderz\Platypus\Exceptions\AuthorizationFailedException;

final class Resource
{
    private function __construct(
        public ResourceId $resourceId,
        public ResourceType $resourceType,
        public Attributes $attributes,
    ) {
    }

    public static function restore(string $resourceId, string $resourceType, array $attributes): self
    {
        return new self(ResourceId::of($resourceId), ResourceType::of($resourceType), Attributes::of($attributes));
    }

    public function appendAttributes(array $newAttributes, bool $replace = true): void
    {
        $oldAttributes = $this->attributes->toArray();
        $attributes = $replace ? array_merge($oldAttributes, $newAttributes) : array_merge($newAttributes, $oldAttributes);
        $this->attributes = Attributes::of($attributes);
    }

    /** @throws AuthorizationFailedException */
    public function attr(string $attributeName): Attribute
    {
        return $this->attributes->attr($attributeName);
    }

    public function isCollaborative(): bool
    {
        return $this(Attribute::WORKSPACE_ID) && !$this->resourceType->equals(ResourceType::RELATION());
    }

    public function __invoke(string $attributeName)
    {
        return $this->attributes->getValue($attributeName);
    }
}
