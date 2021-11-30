<?php

namespace Cardz\Core\Plans\Presentation\Controllers\Http\Plan\Commands;

use Cardz\Core\Plans\Application\Commands\Plan\AddPlan;
use Illuminate\Foundation\Http\FormRequest;

final class AddPlanRequest extends FormRequest
{
    private string $workspaceId;

    private string $description;

    public function rules(): array
    {
        return [
            'workspaceId' => 'required',
            'description' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'workspaceId.required' => 'workspaceId required',
            'description.required' => 'description required',
        ];
    }

    public function passedValidation(): void
    {
        $this->workspaceId = $this->input('workspaceId');
        $this->description = $this->input('description');
    }

    public function toCommand(): AddPlan
    {
        return AddPlan::of(
            $this->workspaceId,
            $this->description,
        );
    }

}
