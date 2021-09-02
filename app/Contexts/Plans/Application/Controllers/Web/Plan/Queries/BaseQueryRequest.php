<?php

namespace App\Contexts\Plans\Application\Controllers\Web\Plan\Queries;

use App\Contexts\Plans\Domain\Model\Plan\PlanId;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseQueryRequest extends FormRequest
{
    protected const RULES = [
        'planId' => 'required',
    ];

    protected const MESSAGES = [
        'planId.required' => 'planId required',
    ];

    public string $planId;

    public function rules(): array
    {
        return array_merge(self::RULES, static::RULES);
    }

    public function messages(): array
    {
        return array_merge(self::MESSAGES, static::MESSAGES);
    }

    public function passedValidation(): void
    {
        $this->planId = $this->input('planId');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'planId' => $this->route('planId'),
        ]);
    }

}
