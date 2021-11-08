<?php

namespace App\Contexts\Authorization\Domain;

use App\Shared\Infrastructure\Authorization\Abac\Attributes;

final class AuthorizationObject
{
    private function __construct(
        private string $objectId,
        private Attributes $attributes,
    ) {
        $this->attributes['id'] = $this->objectId;
    }

    public static function of(string $objectId, Attributes $attributes): self
    {
        return new self($objectId, $attributes);
    }

    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }
}