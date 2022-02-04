<?php

namespace Cardz\Support\MobileAppGateway\Tests\Scenarios;

use Cardz\Core\Workspaces\Tests\Support\Builders\WorkspaceBuilder;
use Cardz\Support\MobileAppGateway\Config\Routes\RouteName;

class WorkspaceTest extends BaseScenarioTestCase
{
    public function test_workspace_can_be_kept()
    {
        $this->persistEnvironment();
        $keeperInfo = $this->environment->keeperInfos[0];
        $this->setAuthTokenFor($keeperInfo);

        $workspaceTemplate = WorkspaceBuilder::make()->withKeeperId($keeperInfo->id)->build();
        $response = $this->routePost(RouteName::ADD_WORKSPACE, [
            ...$workspaceTemplate->profile->toArray(),
        ]);
        $response->assertSuccessful();

        $workspaceData = $response->json();
        $this->assertEquals($response->json('keeperId'), $keeperInfo->id);
        $this->assertEquals([
            'name' => $workspaceData['name'],
            'description' => $workspaceData['description'],
            'address' => $workspaceData['address'],
        ],
            $workspaceTemplate->profile->toArray()
        );

        $changed = 'Changed';
        $response = $this->routePut(RouteName::CHANGE_PROFILE,
            ['workspaceId' => $workspaceData['workspaceId']],
            ['name' => $changed, 'description' => $changed, 'address' => $changed],
        );
        $workspaceData = $response->json();
        $response->assertSuccessful();
        $this->assertEquals([
            'name' => $workspaceData['name'],
            'description' => $workspaceData['description'],
            'address' => $workspaceData['address'],
        ], [
            'name' => $changed,
            'description' => $changed,
            'address' => $changed,
        ]);

    }
}
