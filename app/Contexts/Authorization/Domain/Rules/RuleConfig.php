<?php

namespace App\Contexts\Authorization\Domain\Rules;

use App\Contexts\Authorization\Domain\Permissions\AuthorizationPermission;
use App\Contexts\Authorization\Domain\Policies\AllowForCollaborators;
use App\Contexts\Authorization\Domain\Policies\AllowForKeeper;
use App\Contexts\Authorization\Domain\Policies\DenyForKeeper;
use App\Shared\Contracts\Authorization\Abac\RuleInterface;
use App\Shared\Infrastructure\Authorization\Abac\AbacRule;

final class RuleConfig
{
    /**
     * @var array
     */
    private array $rules;

    private function __construct(RuleInterface ...$rules)
    {
        $this->rules = $rules;
    }

    public static function make(): self
    {
        $allowForCollaborators = new AllowForCollaborators();
        $allowForKeeper = new AllowForKeeper();
        $denyForKeeper = new DenyForKeeper();

        $rules = [
            AbacRule::of(AuthorizationPermission::WORKSPACE_VIEW(), $allowForCollaborators),
            AbacRule::of(AuthorizationPermission::WORKSPACE_CHANGE_PROFILE(), $allowForKeeper),
            AbacRule::of(AuthorizationPermission::PLAN_ADD(), $allowForCollaborators),
            AbacRule::of(AuthorizationPermission::PLAN_VIEW(), $allowForCollaborators),
            AbacRule::of(AuthorizationPermission::PLAN_CHANGE(), $allowForCollaborators),
            AbacRule::of(AuthorizationPermission::PLAN_CARD_ADD(), $allowForCollaborators),
            AbacRule::of(AuthorizationPermission::CARD_VIEW(), $allowForCollaborators),
            AbacRule::of(AuthorizationPermission::CARD_CHANGE(), $allowForCollaborators),
            AbacRule::of(AuthorizationPermission::INVITE_PROPOSE(), $allowForKeeper),
            AbacRule::of(AuthorizationPermission::INVITE_DISCARD(), $allowForKeeper),
            AbacRule::of(AuthorizationPermission::COLLABORATION_LEAVE(), $allowForCollaborators, $denyForKeeper),
            AbacRule::of(AuthorizationPermission::NULL_PERMISSION()),
        ];
        return new self(...$rules);
    }

    /**
     * @return RuleInterface[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
