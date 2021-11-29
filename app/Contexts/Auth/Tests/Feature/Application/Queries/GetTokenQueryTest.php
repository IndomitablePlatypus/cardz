<?php

namespace App\Contexts\Auth\Tests\Feature\Application\Queries;

use App\Contexts\Auth\Application\Queries\GetToken;
use App\Contexts\Auth\Domain\Persistence\Contracts\UserRepositoryInterface;
use App\Contexts\Auth\Tests\Support\Builders\UserBuilder;
use App\Shared\Infrastructure\Tests\ApplicationTestTrait;
use App\Shared\Infrastructure\Tests\BaseTestCase;

class GetTokenQueryTest extends BaseTestCase
{
    use ApplicationTestTrait;

    public function test_token_can_be_obtained()
    {
        $userBuilder = UserBuilder::make();
        $user = $userBuilder->build();
        $this->getUserRepository()->persist($user);

        $query = GetToken::of($user->identity, $userBuilder->plainTextPassword, $userBuilder->faker->word());
        $token = $this->queryBus()->execute($query);

        $this->assertNotEmpty($token);
        $this->assertMatchesRegularExpression('/\d+\|.{40}/', $token);
    }

    protected function getUserRepository(): UserRepositoryInterface
    {
        return $this->app->make(UserRepositoryInterface::class);
    }
}
