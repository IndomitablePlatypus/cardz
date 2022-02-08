<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class ProfileResponseSchema extends SchemaFactory implements Reusable
{
    use SchemaFakerTrait;

    /**
     * @return SchemaContract
     * @throws InvalidArgumentException
     */
    public function build(): SchemaContract
    {
        $profileId = Schema::string('profileId')
            ->format(Schema::FORMAT_UUID)
            ->nullable(false)
            ->description('Profile Id');

        $phone = Schema::string('phone')
            ->description('Phone')
            ->example($this->phone());

        $name = Schema::string('name')
            ->description('Customer name')
            ->example($this->name());

        return Schema::object('Profile')
            ->required($profileId, $name, $phone)
            ->properties($profileId, $name, $phone);
    }

}
