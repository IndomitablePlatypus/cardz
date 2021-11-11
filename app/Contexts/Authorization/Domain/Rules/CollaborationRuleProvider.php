<?php

namespace App\Contexts\Authorization\Domain\Rules;

use App\Contexts\Authorization\Dictionary\PermissionRepository;
use App\Contexts\Authorization\Domain\Policies\AllowOnlyForCollaborators;
use App\Contexts\Authorization\Domain\Policies\AllowOnlyForKeeper;
use App\Contexts\Authorization\Domain\Policies\DenyForKeeper;
use App\Shared\Infrastructure\Authorization\Abac\AbacRule;

class CollaborationRuleProvider
{
    public array $rules = [];

    public function __construct()
    {
        $allowOnlyForKeeper = new AllowOnlyForKeeper();
        $allowOnlyForCollaborators = new AllowOnlyForCollaborators();
        $denyForKeeper = new DenyForKeeper();
        $this->rules = [
            AbacRule::of(PermissionRepository::INVITES_PROPOSE(), $allowOnlyForKeeper),
            AbacRule::of(PermissionRepository::INVITES_DISCARD(), $allowOnlyForKeeper),
            AbacRule::of(PermissionRepository::COLLABORATION_LEAVE(), $allowOnlyForCollaborators, $denyForKeeper),
        ];
    }
}
