<?php

namespace App\Contexts\Authorization\Domain\Permissions;

use App\Contexts\Authorization\Dictionary\ObjectTypeName;
use App\Shared\Exceptions\AuthorizationFailedException;
use App\Shared\Infrastructure\Authorization\Abac\AbacPermission;

/**
 * @method static self WORKSPACE_VIEW()
 * @method static self WORKSPACE_CHANGE_PROFILE()
 * @method static self PLAN_ADD()
 * @method static self PLAN_VIEW()
 * @method static self PLAN_CHANGE()
 * @method static self PLAN_CARD_ADD()
 * @method static self CARD_VIEW()
 * @method static self CARD_CHANGE()
 * @method static self INVITE_PROPOSE()
 * @method static self INVITE_DISCARD()
 * @method static self COLLABORATION_LEAVE()
 * @method static self NULL_PERMISSION()
 */
final class AuthorizationPermission extends AbacPermission implements ObjectTypePrescribingPermissionInterface
{
    public const WORKSPACE_VIEW = 'workspace.view';
    public const WORKSPACE_CHANGE_PROFILE = 'workspace.change_profile';

    public const PLAN_ADD = 'workspace.plan.add';
    public const PLAN_VIEW = 'workspace.plan.view';

    public const PLAN_CHANGE = 'plan.change';
    public const PLAN_CARD_ADD = 'plan.card.add';

    public const CARD_VIEW = 'card.view';
    public const CARD_CHANGE = 'card.change';

    public const INVITE_PROPOSE = 'workspace.invite.propose';
    public const INVITE_DISCARD = 'workspace.invite.discard';

    public const COLLABORATION_LEAVE = 'workspace.collaboration.leave';

    public const NULL_PERMISSION = 'null';

    public function getObjectType(): ObjectTypeName
    {
        $permissionKey = explode('.', (string) $this)[0];
        return ObjectTypeName::isValid($permissionKey)
            ? new ObjectTypeName($permissionKey)
            : throw new AuthorizationFailedException("Unknown objectType");
    }

}
