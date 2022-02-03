<?php

namespace Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Customer\Contracts;

use Cardz\Support\MobileAppGateway\Domain\ReadModel\Customer\CustomerProfile;

interface CustomerProfileReadStorageInterface
{
    public function byCustomerId(string $customerId): CustomerProfile;
}
