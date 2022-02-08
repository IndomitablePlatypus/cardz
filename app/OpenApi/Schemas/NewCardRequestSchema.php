<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class NewCardRequestSchema extends SchemaFactory implements Reusable
{
    /**
     * @return SchemaContract
     * @throws InvalidArgumentException
     */
    public function build(): SchemaContract
    {
        $planId = Schema::string('planId')
            ->format(Schema::FORMAT_UUID)
            ->description('Plan id');

        $customerId = Schema::string('customerId')
            ->format(Schema::FORMAT_UUID)
            ->description('Customer id');

        return Schema::object('NewCard')
            ->description('New card request')
            ->required($planId, $customerId)
            ->properties($planId, $customerId);
    }

}
