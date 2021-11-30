<?php

namespace Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class GetIssuedCardsRequest extends FormRequest
{
    public string $customerId;

    public function rules(): array
    {
        return [
            'customerId' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'customerId.required' => 'customerId required',
        ];
    }

    public function passedValidation(): void
    {
        $this->customerId = $this->input('customerId');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'customerId' => $this->user()->id,
        ]);
    }
}
