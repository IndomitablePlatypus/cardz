<?php

namespace App\Contexts\Plans\Application\Controllers\Web\Plan\Commands;

use App\Contexts\Plans\Domain\Model\Plan\PlanId;
use App\Contexts\Plans\Domain\Model\Shared\WorkspaceId;

class AddPlanRequest extends BaseCommandRequest
{
    public ?string $description;

    public function passedValidation(): void
    {
        $this->workspaceId = WorkspaceId::of($this->input('workspaceId'));
        $this->description = $this->input('description');
    }

    public function messages()
    {
        return [
            'workspaceId.required' => 'workspaceId required',
            'description.required' => 'description required',
        ];
    }

    protected function inferPlanId(): void
    {
        $this->planId = PlanId::make();
    }
}
