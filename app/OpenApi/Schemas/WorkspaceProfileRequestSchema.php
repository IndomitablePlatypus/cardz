<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class WorkspaceProfileRequestSchema extends SchemaFactory implements Reusable
{
    use SchemaFakerTrait;

    /**
     * @return SchemaContract
     * @throws InvalidArgumentException
     */
    public function build(): SchemaContract
    {
        $name = Schema::string('name')
            ->description('Workspace (business) title')
            ->example($this->company());

        $description = Schema::string('description')
            ->description('Workspace (business) description')
            ->example($this->text());

        $address = Schema::string('address')
            ->description('Workspace (business) address')
            ->example($this->address());

        return Schema::object('WorkspaceProfile')
            ->description('Workspace profile request')
            ->required($name, $description, $address)
            ->properties($name, $description, $address);
    }

}
