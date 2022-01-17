<?php

namespace Cardz\Support\MobileAppGateway\Application\Middleware\Authorization;

use Cardz\Generic\Authorization\Application\AuthorizationBusInterface;
use Cardz\Generic\Authorization\Application\Queries\IsAllowed;
use Cardz\Support\MobileAppGateway\Application\Exceptions\AccessDeniedException;
use Cardz\Support\MobileAppGateway\Config\Authorization\RouteNameToPermissionMap;
use Closure;
use Codderz\Platypus\Infrastructure\Support\GuidBasedImmutableId;
use Illuminate\Http\Request;

class AuthorizationMiddleware
{
    public function __construct(
        private AuthorizationBusInterface $authorizationBus,
    ) {
    }

    /**
     * @throws AccessDeniedException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $this->authorize($request);
        return $next($request);
    }

    /**
     * @throws AccessDeniedException
     */
    private function authorize(Request $request): void
    {
        $subjectId = $request->user()?->id;
        if (!$subjectId) {
            return;
        }

        $isAllowedQuery = $this->requestToQuery($request);
        if (!$this->authorizationBus->execute($isAllowedQuery)) {
            $message = sprintf(
                "Subject %s is not authorized for %s %s",
                $isAllowedQuery->subjectId,
                $isAllowedQuery->permission->resourceType(),
                $isAllowedQuery->objectId,
            );
            throw new AccessDeniedException($message);
        }
    }

    private function requestToQuery(Request $request): IsAllowed
    {
        $routeName = $request->route()?->getName();
        $permission = RouteNameToPermissionMap::map($routeName);

        $objectIdName = $permission->resourceType()->idField();
        $objectId = $objectIdName !== null ? GuidBasedImmutableId::of($request->$objectIdName) : null;

        return IsAllowed::of(
            $permission,
            GuidBasedImmutableId::of($request->user()?->id),
            $objectId,
        );
    }
}
