<?php

namespace Cardz\Support\MobileAppGateway\Integration\Contracts;

interface PlansContextInterface
{
    public function add(string $workspaceId, string $name, string $description): string;

    public function archive(string $planId): string;

    public function changeProfile(string $planId, string $name, string $description): string;

    public function launch(string $planId, string $expirationDate): string;

    public function stop(string $planId): string;

    public function addRequirement(string $planId, string $description): string;

    public function changeRequirement(string $requirementId, string $description): string;

    public function removeRequirement(string $requirementId): string;
}
