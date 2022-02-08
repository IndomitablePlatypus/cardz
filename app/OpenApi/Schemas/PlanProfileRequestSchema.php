<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class PlanProfileRequestSchema extends SchemaFactory implements Reusable
{
    use SchemaFakerTrait;

    /**
     * @return SchemaContract
     * @throws InvalidArgumentException
     */
    public function build(): SchemaContract
    {
        $name = Schema::string('name')
            ->description('Plan name')
            ->example($this->sentence());

        $description = Schema::string('description')
            ->description('Plan description')
            ->example($this->text());

        return Schema::object('PlanProfile')
            ->description('Plan profile request')
            ->required($name, $description)
            ->properties($name, $description);
    }

}
