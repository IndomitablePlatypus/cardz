<?php

namespace App\Contexts\Collaboration\Presentation\Controllers\Http\Invite\Commands;

use App\Contexts\Collaboration\Application\Commands\Invite\ProposeInvite;
use App\Contexts\Collaboration\Application\Commands\Invite\ProposeInviteCommandInterface;
use Illuminate\Foundation\Http\FormRequest;

final class ProposeInviteRequest extends FormRequest
{
    public string $keeperId;
    public string $memberId;
    public string $workspaceId;

    public function rules(): array
    {
        return [
            'keeperId' => 'required',
            'memberId' => 'required',
            'workspaceId' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'keeperId.required' => 'keeperId required',
            'memberId.required' => 'memberId required',
            'workspaceId.required' => 'workspaceId required',
        ];
    }

    public function passedValidation(): void
    {
        $this->keeperId = $this->input('keeperId');
        $this->memberId = $this->input('memberId');
        $this->workspaceId = $this->input('workspaceId');
    }

    public function toCommand(): ProposeInviteCommandInterface
    {
        return ProposeInvite::of($this->keeperId, $this->memberId, $this->workspaceId);
    }

}
