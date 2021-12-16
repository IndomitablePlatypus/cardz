<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class IssuedCardResponseSchema extends SchemaFactory implements Reusable
{
    public function build(): SchemaContract
    {
        $cardId = Schema::string('cardId')->format(Schema::FORMAT_UUID)->description('Card Id');
        $planId = Schema::string('planId')->format(Schema::FORMAT_UUID)->description('Plan Id');
        $customerId = Schema::string('customerId')->format(Schema::FORMAT_UUID)->description('Customer Id');
        $satisfied = Schema::boolean('satisfied')->description('Whether all requirements to receive a bonus are satisfied');
        $completed = Schema::boolean('completed')->description('Whether customer has received the bonus for this card');

        $achievement = Schema::object()->properties(
            Schema::string('achievementId')->format(Schema::FORMAT_UUID)->description('Achievement Id = corresponding requirement id'),
            Schema::string('description')->description('Achievement description = corresponding requirement description'),
        );
        $achievements = Schema::array('achievements')->items($achievement)->description('Achieved requirements');

        $requirement = Schema::object()->properties(
            Schema::string('requirementId')->format(Schema::FORMAT_UUID)->description('Requirement id'),
            Schema::string('description')->description('Requirement description'),
        );
        $requirements = Schema::array('requirements')->items($requirement)->description('All requirements');

        return Schema::object()->properties($cardId, $planId, $customerId, $satisfied, $completed, $achievements, $requirements);
    }

}
