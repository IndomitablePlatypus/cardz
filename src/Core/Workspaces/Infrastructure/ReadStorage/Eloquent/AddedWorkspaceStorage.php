<?php

namespace Cardz\Core\Workspaces\Infrastructure\ReadStorage\Eloquent;

use App\Models\Workspace as EloquentWorkspace;
use Cardz\Core\Workspaces\Domain\Model\Workspace\Profile;
use Cardz\Core\Workspaces\Domain\ReadModel\AddedWorkspace;
use Cardz\Core\Workspaces\Domain\ReadModel\Contracts\AddedWorkspaceStorageInterface;
use Codderz\Platypus\Infrastructure\Support\PropertiesExtractorTrait;
use JetBrains\PhpStorm\ArrayShape;
use Carbon\Carbon;

class AddedWorkspaceStorage implements AddedWorkspaceStorageInterface
{
    use PropertiesExtractorTrait;

    public function persist(AddedWorkspace $addedWorkspace): void
    {
        EloquentWorkspace::query()->updateOrCreate(
            ['id' => $addedWorkspace->workspaceId],
            $this->workspaceAsData($addedWorkspace)
        );
    }

    public function take(?string $workspaceId): ?AddedWorkspace
    {
        if ($workspaceId === null) {
            return null;
        }
        /** @var EloquentWorkspace $eloquentWorkspace */
        $eloquentWorkspace = EloquentWorkspace::query()->find($workspaceId);
        if ($eloquentWorkspace === null) {
            return null;
        }
        return $this->readWorkspaceFromData($eloquentWorkspace);
    }

    #[ArrayShape(['id' => "string", 'keeper_id' => "string", 'profile' => "array", 'added_at' => Carbon::class])]
    private function workspaceAsData(AddedWorkspace $workspace): array
    {
        return [
            'id' => $workspace->workspaceId,
            'keeper_id' => $workspace->keeperId,
            'profile' => $workspace->profile->toArray(),
            'added_at' => $workspace->added,
        ];
    }

    private function readWorkspaceFromData(EloquentWorkspace $eloquentWorkspace): AddedWorkspace
    {
        $profile = is_string($eloquentWorkspace->profile) ? json_try_decode($eloquentWorkspace->profile, true) : $eloquentWorkspace->profile;
        return new AddedWorkspace(
            $eloquentWorkspace->id,
            $eloquentWorkspace->keeper_id,
            Profile::of(...$profile),
            $eloquentWorkspace->added_at,
        );
    }
}
