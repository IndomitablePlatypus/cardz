<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class IssuedCardResponseSchema extends SchemaFactory implements Reusable
{
    use SchemaFakerTrait;

    public function build(): SchemaContract
    {
        $cardId = Schema::string('cardId')
            ->format(Schema::FORMAT_UUID)
            ->nullable(false)
            ->description('Card Id');

        $workspaceName = Schema::string('workspaceName')
            ->nullable(false)
            ->description('Workspace (business) name')
            ->example($this->company());

        $workspaceAddress = Schema::string('workspaceAddress')
            ->nullable(false)
            ->description('Workspace (business) address')
            ->example($this->address());

        $customerId = Schema::string('customerId')
            ->format(Schema::FORMAT_UUID)
            ->nullable(false)
            ->description('Customer Id');

        $description = Schema::string('description')
            ->nullable(false)
            ->description('Card (plan) description')
            ->example($this->faker()->text());

        $satisfied = Schema::boolean('satisfied')
            ->nullable(false)
            ->description('Whether all requirements to receive a bonus are satisfied');

        $completed = Schema::boolean('completed')
            ->nullable(false)
            ->description('Whether customer has received the bonus for this card');

        $blocked = Schema::boolean('blocked')
            ->nullable(false)
            ->description('Whether the card has been blocked');

        $achievement = Schema::object()->properties(
            Schema::string('achievementId')
                ->nullable(false)
                ->format(Schema::FORMAT_UUID)
                ->description('Achievement Id = corresponding requirement id'),

            Schema::string('description')
                ->nullable(false)
                ->description('Achievement description = corresponding requirement description')
                ->example($this->text()),
        )->required('achievementId', 'description');

        $achievements = Schema::array('achievements')
            ->items($achievement)
            ->description('Achieved requirements')
            ->example($this->text());

        $requirement = Schema::object()->properties(
            Schema::string('requirementId')
                ->nullable(false)
                ->format(Schema::FORMAT_UUID)->description('Requirement id'),

            Schema::string('description')
                ->nullable(false)
                ->description('Requirement description')
                ->example($this->text()),
        )->required('requirementId', 'description');

        $requirements = Schema::array('requirements')
            ->items($requirement)
            ->description('All requirements');

        return Schema::object('IssuedCard')
            ->properties($cardId, $workspaceName, $workspaceAddress, $customerId, $description, $satisfied, $completed, $blocked, $achievements, $requirements)
            ->required($cardId, $workspaceName, $workspaceAddress, $customerId, $description, $satisfied, $completed, $blocked, $achievements, $requirements);
    }

}
