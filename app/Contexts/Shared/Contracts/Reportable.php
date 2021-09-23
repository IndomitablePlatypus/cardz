<?php

namespace App\Contexts\Shared\Contracts;

interface Reportable
{
    public function __toString(): string;

    public function id(): string;

    public function payload(): array;
}
