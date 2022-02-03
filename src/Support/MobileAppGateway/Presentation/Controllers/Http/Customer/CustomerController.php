<?php

namespace Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer;

use App\OpenApi\Requests\Customer\GetTokenRequestBody;
use App\OpenApi\Requests\Customer\RegisterRequestBody;
use App\OpenApi\Responses\ApiAccessTokenResponse;
use App\OpenApi\Responses\ClearTokensResponse;
use App\OpenApi\Responses\CustomerIdResponse;
use App\OpenApi\Responses\CustomerProfileResponse;
use App\OpenApi\Responses\CustomerWorkspacesResponse;
use App\OpenApi\Responses\Errors\AuthenticationExceptionResponse;
use App\OpenApi\Responses\Errors\AuthorizationExceptionResponse;
use App\OpenApi\Responses\Errors\NotFoundResponse;
use App\OpenApi\Responses\Errors\UnexpectedExceptionResponse;
use App\OpenApi\Responses\Errors\UserAlreadyRegisteredExceptionResponse;
use App\OpenApi\Responses\Errors\ValidationErrorResponse;
use App\OpenApi\Responses\IssuedCardResponse;
use App\OpenApi\Responses\IssuedCardsResponse;
use App\OpenApi\SecuritySchemes\BearerTokenSecurityScheme;
use Cardz\Support\MobileAppGateway\Application\Services\Customer\CustomerAppService;
use Cardz\Support\MobileAppGateway\Config\Routes\RouteName;
use Cardz\Support\MobileAppGateway\Infrastructure\Exceptions\IssuedCardNotFoundException;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\BaseController;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\Requests\GetIssuedCardRequest;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\Requests\GetIssuedCardsRequest;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\Requests\GetTokenRequest;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Guid\Guid;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CustomerController extends BaseController
{
    public function __construct(
        private CustomerAppService $customerAppService,
    ) {
    }

    /**
     * Get authorized user id
     *
     * Returns id of the authenticated user.
     */
    #[OpenApi\Operation(id: RouteName::CUSTOMER_ID, tags: ['customer'], security: BearerTokenSecurityScheme::class)]
    #[OpenApi\Response(factory: CustomerIdResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: AuthorizationExceptionResponse::class, statusCode: 403)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function getId(): JsonResponse
    {
        return $this->response($this->customerAppService->getCustomerId());
    }

    /**
     * Get authorized user profile
     *
     * Returns profile.
     */
    #[OpenApi\Operation(id: RouteName::CUSTOMER_PROFILE, tags: ['customer'], security: BearerTokenSecurityScheme::class)]
    #[OpenApi\Response(factory: CustomerProfileResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: AuthorizationExceptionResponse::class, statusCode: 403)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function getProfile(): JsonResponse
    {
        return $this->response($this->customerAppService->getCustomerProfile());
    }

    /**
     * Get user token
     *
     * Returns new API user token (for basic bearer auth). Requires identity, password and device name.
     */
    #[OpenApi\Operation(id: RouteName::GET_TOKEN, tags: ['customer'])]
    #[OpenApi\RequestBody(factory: GetTokenRequestBody::class)]
    #[OpenApi\Response(factory: ApiAccessTokenResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: ValidationErrorResponse::class, statusCode: 422)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function getToken(GetTokenRequest $request): JsonResponse
    {
        return $this->response($this->customerAppService->getToken(
            $request->identity,
            $request->password,
            $request->deviceName,
        ));
    }

    /**
     * Clear user tokens
     *
     * Removes all existing access tokens for the current user. (I.e. logout)
     */
    #[OpenApi\Operation(id: RouteName::CLEAR_TOKENS, tags: ['customer'])]
    #[OpenApi\Response(factory: ClearTokensResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function clearTokens(): JsonResponse
    {
        return $this->response($this->customerAppService->clearTokens(Auth::id()));
    }

    /**
     * Register user
     *
     * Registers new user with email OR phone, password, device name (for token). Returns new auth token.
     */
    #[OpenApi\Operation(id: RouteName::REGISTER, tags: ['customer'])]
    #[OpenApi\RequestBody(factory: RegisterRequestBody::class)]
    #[OpenApi\Response(factory: ApiAccessTokenResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: UserAlreadyRegisteredExceptionResponse::class, statusCode: 400)]
    #[OpenApi\Response(factory: ValidationErrorResponse::class, statusCode: 422)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->response($this->customerAppService->register(
            $request->email,
            $request->phone,
            $request->name,
            $request->password,
            $request->deviceName,
        ));
    }

    /**
     * User cards
     *
     * Returns all active cards for the current user.
     */
    #[OpenApi\Operation(id: RouteName::CUSTOMER_CARDS, tags: ['customer'], security: BearerTokenSecurityScheme::class)]
    #[OpenApi\Response(factory: IssuedCardsResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function getCards(GetIssuedCardsRequest $request): JsonResponse
    {
        return $this->response($this->customerAppService->getIssuedCards($request->customerId));
    }

    /**
     * User card
     *
     * Returns an active card, owned by the current user, by its id.
     * @param Guid $cardId Card GUID
     */
    #[OpenApi\Operation(id: RouteName::CUSTOMER_CARD, tags: ['customer'], security: BearerTokenSecurityScheme::class)]
    #[OpenApi\Response(factory: IssuedCardResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: AuthenticationExceptionResponse::class, statusCode: 401)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function getCard(GetIssuedCardRequest $request): JsonResponse
    {
        return $this->response($this->customerAppService->getIssuedCard($request->customerId, $request->cardId));
    }

    /**
     * Workspaces
     *
     * Returns all workspaces
     */
    #[OpenApi\Operation(id: RouteName::CUSTOMER_WORKSPACES, tags: ['customer'])]
    #[OpenApi\Response(factory: CustomerWorkspacesResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: UnexpectedExceptionResponse::class, statusCode: 500)]
    public function getWorkspaces(): JsonResponse
    {
        return $this->response($this->customerAppService->getCustomerWorkspaces());
    }

}
