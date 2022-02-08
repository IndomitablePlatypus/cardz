<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class BusinessCardResponseSchema extends SchemaFactory implements Reusable
{
    use SchemaFakerTrait;

    public function build(): SchemaContract
    {
        $cardId = Schema::string('cardId')
            ->format(Schema::FORMAT_UUID)
            ->nullable(false)
            ->description('Card Id');

        $planId = Schema::string('planId')
            ->format(Schema::FORMAT_UUID)
            ->nullable(false)
            ->description('Plan Id');

        $customerId = Schema::string('customerId')
            ->format(Schema::FORMAT_UUID)
            ->nullable(false)
            ->description('Customer Id');

        $isIssued = Schema::boolean('isIssued')
            ->nullable(false)
            ->description('Whether the card is issued');

        $isSatisfied = Schema::boolean('isSatisfied')
            ->nullable(false)
            ->description('Whether all the requirements to receive a bonus are satisfied');

        $isCompleted = Schema::boolean('isCompleted')
            ->nullable(false)
            ->description('Whether the customer has received the bonus for this card');

        $isRevoked = Schema::boolean('isRevoked')
            ->nullable(false)
            ->description('Whether the card has been revoked');

        $isBlocked = Schema::boolean('isBlocked')
            ->nullable(false)
            ->description('Whether the card has been blocked');

        $achievement = Schema::object()->properties(
            Schema::string('achievementId')
                ->format(Schema::FORMAT_UUID)
                ->nullable(false)
                ->description('Achievement Id = corresponding requirement id'),

            Schema::string('description')
                ->nullable(false)
                ->description('Achievement description = corresponding requirement description')
                ->example($this->text()),
        )->required('achievementId', 'description');

        $achievements = Schema::array('achievements')
            ->items($achievement)
            ->description('Achieved requirements');

        $requirement = Schema::object()->properties(
            Schema::string('requirementId')
                ->format(Schema::FORMAT_UUID)
                ->nullable(false)
                ->description('Requirement id'),

            Schema::string('description')
                ->nullable(false)
                ->description('Requirement description')
                ->example($this->text()),
        )->required('requirementId', 'description');

        $requirements = Schema::array('requirements')
            ->items($requirement)
            ->description('All requirements');

        return Schema::object('BusinessCard')
            ->required($cardId, $planId, $customerId, $isIssued, $isSatisfied, $isCompleted, $isRevoked, $isBlocked, $achievements, $requirements)
            ->properties($cardId, $planId, $customerId, $isIssued, $isSatisfied, $isCompleted, $isRevoked, $isBlocked, $achievements, $requirements);
    }

}
