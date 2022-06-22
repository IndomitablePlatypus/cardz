<?php

namespace Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Workspace;

use App\OpenApi\Responses\CollaboratorIdResponse;
use App\OpenApi\Responses\Errors\AuthenticationExceptionResponse;
use App\OpenApi\Responses\Errors\AuthorizationExceptionResponse;
use App\OpenApi\Responses\Errors\DomainExceptionResponse;
use App\OpenApi\Responses\Errors\NotFoundResponse;
use App\OpenApi\Responses\Errors\UnexpectedExceptionResponse;
use App\OpenApi\Responses\InviteIdResponse;
use Cardz\Support\MobileAppGateway\Application\Services\Workspace\CollaborationAppService;
use Cardz\Support\MobileAppGateway\Config\Routes\RouteName;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\BaseController;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Workspace\Commands\Collaboration\FireCollaboratorRequest;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Workspace\Commands\Collaboration\InviteRequest;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Workspace\Commands\Collaboration\LeaveCollaborationRequest;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Workspace\Commands\Collaboration\ProposeInviteRequest;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Guid\Guid;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CollaborationController extends BaseController
{
    public function __construct(
        private CollaborationAppService $collaborationAppService,
    ) {
    }

    /**
     * Propose invite
     *
     * Returns id of the new invite to collaborate on the workspace.
     * Requires user to be the owner of the current workspace.
     * @param Guid $workspaceId Workspace GUID
     */
    #[OpenApi\Operation(id: RouteName::PROPOSE_INVITE, tags: ['business', 'collaboration'])]
    #[OpenApi\Response(factory: InviteIdResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: AuthorizationExceptionResponse::class, statusCode: 403)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function propose(ProposeInviteRequest $request): JsonResponse
    {
        return $this->response($this->collaborationAppService->propose($request->collaboratorId, $request->workspaceId));
    }

    /**
     * Accept invite
     *
     * Accepts an invitation to collaborate. Authorizes user to work in the current workspace.
     * @param Guid $workspaceId Workspace GUID
     * @param Guid $inviteId Invite GUID
     */
    #[OpenApi\Operation(id: RouteName::ACCEPT_INVITE, tags: ['business', 'collaboration'])]
    #[OpenApi\Response(factory: InviteIdResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: DomainExceptionResponse::class, statusCode: 400)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function accept(InviteRequest $request): JsonResponse
    {
        return $this->response($this->collaborationAppService->accept($request->collaboratorId, $request->inviteId));
    }

    /**
     * Discard invite
     *
     * Returns id of the new invite to collaborate on the workspace.
     * Requires user to be the owner of the current workspace.
     * @param Guid $workspaceId Workspace GUID
     * @param Guid $inviteId Invite GUID
     */
    #[OpenApi\Operation(id: RouteName::DISCARD_INVITE, tags: ['business', 'collaboration'])]
    #[OpenApi\Response(factory: InviteIdResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: AuthorizationExceptionResponse::class, statusCode: 403)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function discard(InviteRequest $request): JsonResponse
    {
        return $this->response($this->collaborationAppService->discard($request->inviteId));
    }

    /**
     * Leave collaboration
     *
     * Rescinds the user ability collaborate in the current workspace.
     * Requires user to be authorized to work in the current workspace. Requires user to NOT be the owner of it.
     * @param Guid $workspaceId Workspace GUID
     */
    #[OpenApi\Operation(id: RouteName::LEAVE_RELATION, tags: ['business', 'collaboration'])]
    #[OpenApi\Response(factory: CollaboratorIdResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: DomainExceptionResponse::class, statusCode: 400)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: AuthorizationExceptionResponse::class, statusCode: 403)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function leave(LeaveCollaborationRequest $request): JsonResponse
    {
        return $this->response($this->collaborationAppService->leave($request->collaboratorId, $request->workspaceId));
    }

    /**
     * Remove collaborator
     *
     * Fires the collaborator from the team. User with the given id will no longer be able to work in the workspace.
     * Requires the current user to be the owner of the workspace.
     * @param Guid $workspaceId Workspace GUID
     * @param Guid $collaboratorId Collaborator GUID
     */
    #[OpenApi\Operation(id: RouteName::FIRE_COLLABORATOR, tags: ['business', 'collaboration'])]
    #[OpenApi\Response(factory: CollaboratorIdResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: DomainExceptionResponse::class, statusCode: 400)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: AuthorizationExceptionResponse::class, statusCode: 403)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function fire(FireCollaboratorRequest $request): JsonResponse
    {
        return $this->response($this->collaborationAppService->fire($request->collaboratorId, $request->workspaceId));
    }
}
