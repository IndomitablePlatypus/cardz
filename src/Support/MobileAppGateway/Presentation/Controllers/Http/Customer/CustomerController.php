<?php

namespace Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer;

use Cardz\Support\MobileAppGateway\Application\Services\Customer\CustomerAppService;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\BaseController;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\Requests\GetIssuedCardRequest;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\Requests\GetIssuedCardsRequest;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\Requests\GetTokenRequest;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;

class CustomerController extends BaseController
{
    public function __construct(
        private CustomerAppService $customerAppService,
    ) {
    }

    public function getId(): JsonResponse
    {
        return $this->response($this->customerAppService->getCustomerId());
    }

    public function getToken(GetTokenRequest $request): JsonResponse
    {
        return $this->response($this->customerAppService->getToken(
            $request->identity,
            $request->password,
            $request->deviceName,
        ));
    }

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

    public function getCards(GetIssuedCardsRequest $request): JsonResponse
    {
        return $this->response($this->customerAppService->getIssuedCards($request->customerId));
    }

    public function getCard(GetIssuedCardRequest $request): JsonResponse
    {
        return $this->response($this->customerAppService->getIssuedCard($request->customerId, $request->cardId));
    }

    public function getWorkspaces(): JsonResponse
    {
        return $this->response($this->customerAppService->getCustomerWorkspaces());
    }

}