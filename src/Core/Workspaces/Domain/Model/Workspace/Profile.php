<?php

namespace Cardz\Core\Workspaces\Domain\Model\Workspace;

use Codderz\Platypus\Contracts\Domain\ValueObjectInterface;
use Codderz\Platypus\Infrastructure\Support\ArrayPresenterTrait;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
final class Profile implements ValueObjectInterface
{
    use ArrayPresenterTrait;

    #[Pure]
    private function __construct(
        private string $name,
        private string $description,
        private string $address,
    ) {
    }

    #[Pure]
    public static function create(string $name, string $description, string $address): self
    {
        return new self($name, $description, $address);
    }

    #[Pure]
    public static function ofData(array $profile): self
    {
        return self::of(...$profile);
    }

    #[Pure]
    public static function of(string $name, string $description, string $address): self
    {
        return new self($name, $description, $address);
    }

}
