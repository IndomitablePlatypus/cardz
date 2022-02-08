<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class PlanExpirationRequestSchema extends SchemaFactory implements Reusable
{
    /**
     * @return SchemaContract
     * @throws InvalidArgumentException
     */
    public function build(): SchemaContract
    {
        $expires = Schema::string('expirationDate')
            ->format(Schema::FORMAT_DATE_TIME)
            ->description('Plan expiration date');

        return Schema::object('PlanExpiration')
            ->description('Plan expiration request')
            ->required($expires)
            ->properties($expires);
    }

}
