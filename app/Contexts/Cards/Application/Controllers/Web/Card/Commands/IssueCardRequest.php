<?php

namespace App\Contexts\Cards\Application\Controllers\Web\Card\Commands;

use App\Contexts\Cards\Domain\Model\Card\CardId;
use App\Contexts\Cards\Domain\Model\Shared\CustomerId;
use App\Contexts\Cards\Domain\Model\Shared\PlanId;

class IssueCardRequest extends BaseCommandRequest
{
    public CustomerId $customerId;

    public PlanId $planId;

    public ?string $description;

    public function passedValidation(): void
    {
        $this->planId = new PlanId($this->input('planId'));
        $this->customerId = new CustomerId($this->input('customerId'));
        $this->description = $this->input('description');
    }

    public function messages()
    {
        return [
            'planId.required' => 'planId required',
            'customerId.required' => 'customerId required',
        ];
    }

    protected function inferCardId(): void
    {
        $this->cardId = new CardId();
    }
}
