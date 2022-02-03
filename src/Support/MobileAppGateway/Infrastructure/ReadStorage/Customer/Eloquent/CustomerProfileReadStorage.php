<?php

namespace Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Customer\Eloquent;

use Cardz\Support\MobileAppGateway\Domain\ReadModel\Customer\CustomerProfile;
use Cardz\Support\MobileAppGateway\Infrastructure\Exceptions\ProfileNotFoundException;
use Cardz\Support\MobileAppGateway\Infrastructure\ReadStorage\Customer\Contracts\CustomerProfileReadStorageInterface;
use Illuminate\Support\Facades\DB;

class CustomerProfileReadStorage implements CustomerProfileReadStorageInterface
{
    public function byCustomerId(string $customerId): CustomerProfile
    {
        $profileData = DB::table('persons', 'p')
            ->join('users as u', 'p.id', '=', 'u.id')
            ->select(['p.id as id', 'p.name as name', 'u.email as email', 'u.phone as phone'])
            ->where('u.id', '=', $customerId)
            ->first();
        if (empty($profileData)) {
            throw new ProfileNotFoundException("Customer $customerId");
        }
        return $this->profileFromData($profileData);
    }

    protected function profileFromData(\stdClass $data): CustomerProfile
    {
        return CustomerProfile::make(
            $data->id,
            $data->name,
            $data->phone ?? $data->email,
        );
    }
}
