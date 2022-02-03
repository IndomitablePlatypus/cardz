<?php

namespace Cardz\Support\MobileAppGateway\Config\Routes;

use MyCLabs\Enum\Enum;

final class RouteName extends Enum
{
    public const REGISTER = 'RegisterCustomer';
    public const GET_TOKEN = 'GetCustomerToken';
    public const CLEAR_TOKENS = 'ClearCustomerToken';
    public const GET_WORKSPACES = 'ListAllWorkspace';
    public const ADD_WORKSPACE = 'AddWorkspace';
    public const GET_PLANS = 'ListAllPlan';
    public const ISSUE_CARD = 'IssueCard';
    public const GET_CARD = 'GetCard';
    public const COMPLETE_CARD = 'CompleteCard';
    public const REVOKE_CARD = 'RevokeCard';
    public const BLOCK_CARD = 'BlockCard';
    public const UNBLOCK_CARD = 'UnblockCard';

    public const NOTE_ACHIEVEMENT = 'NoteCardAchievement';
    public const DISMISS_ACHIEVEMENT = 'DismissCardAchievement';

    public const ADD_PLAN = 'AddPlan';
    public const CHANGE_PLAN_PROFILE = 'ChangePlanProfile';
    public const LAUNCH_PLAN = 'LaunchPlan';
    public const STOP_PLAN = 'StopPlan';
    public const ARCHIVE_PLAN = 'ArchivePlan';
    public const ADD_PLAN_REQUIREMENT = 'AddPlanRequirement';
    public const REMOVE_PLAN_REQUIREMENT = 'RemovePlanRequirement';
    public const CHANGE_PLAN_REQUIREMENT = 'ChangePlanRequirement';
    public const PROPOSE_INVITE = 'ProposeInvite';
    public const ACCEPT_INVITE = 'AcceptInvite';
    public const DISCARD_INVITE = 'DiscardInvite';

    public const LEAVE_RELATION = 'LeaveWorkspace';
    public const FIRE_COLLABORATOR = 'FireCollaborator';

    public const GET_WORKSPACE = 'GetWorkspace';
    public const CHANGE_PROFILE = 'ChangeWorkspaceProfile';
    public const GET_PLAN = 'GetPlan';

    public const CUSTOMER_WORKSPACES = 'GetCustomerWorkspaces';
    public const CUSTOMER_ID = 'GetCustomerId';
    public const CUSTOMER_PROFILE = 'GetCustomerProfile';
    public const CUSTOMER_CARDS = 'GetCustomerCards';
    public const CUSTOMER_CARD = 'GetCustomerCard';
}
