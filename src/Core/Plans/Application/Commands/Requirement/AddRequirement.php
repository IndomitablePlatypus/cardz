<?php

namespace Cardz\Core\Plans\Application\Commands\Requirement;

use Cardz\Core\Plans\Domain\Model\Plan\PlanId;
use Cardz\Core\Plans\Domain\Model\Requirement\RequirementId;

final class AddRequirement implements RequirementCommandInterface
{
    private function __construct(
        private string $requirementId,
        private string $planId,
        private string $description,
    ) {
    }

    public static function of(string $planId, string $description): self
    {
        return new self(PlanId::makeValue(), $planId, $description);
    }

    public function getRequirementId(): RequirementId
    {
        return RequirementId::of($this->requirementId);
    }

    public function getPlanId(): PlanId
    {
        return PlanId::of($this->planId);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

}
