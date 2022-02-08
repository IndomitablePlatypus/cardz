<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class BusinessPlanResponseSchema extends SchemaFactory implements Reusable
{
    use SchemaFakerTrait;

    public function build(): SchemaContract
    {
        $planId = Schema::string('planId')
            ->format(Schema::FORMAT_UUID)
            ->nullable(false)
            ->description('Plan Id');

        $workspaceId = Schema::string('workspaceId')
            ->format(Schema::FORMAT_UUID)
            ->nullable(false)
            ->description('Workspace Id');

        $name = Schema::string('name')
            ->description('Plan name')
            ->nullable(false)
            ->example($this->sentence());

        $description = Schema::string('description')
            ->description('Plan description')
            ->nullable(false)
            ->example($this->text());

        $isLaunched = Schema::boolean('isLaunched')
            ->nullable(false)
            ->description('Whether the plan is launched');

        $isStopped = Schema::boolean('isStopped')
            ->nullable(false)
            ->description('Whether the plan is stopped');

        $isArchived = Schema::boolean('isArchived')
            ->nullable(false)
            ->description('Whether the plan is archived');

        $expirationDate = Schema::string('expirationDate')
            ->nullable(true)
            ->format(Schema::FORMAT_DATE_TIME)
            ->description('Plan expiration date');

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

        return Schema::object('BusinessPlan')
            ->properties($planId, $workspaceId, $name, $description, $isLaunched, $isStopped, $isArchived, $expirationDate, $requirements)
            ->required($planId, $workspaceId, $name, $description, $isLaunched, $isStopped, $isArchived, $expirationDate, $requirements);
    }

}
