<?php

namespace App\Http\Services\Project;

use App\Http\Repositories\BaseRepository;
use App\Models\Project;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function createProject(array $data): Model
    {
        return $this->create($data);
    }

    public function projectList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: Project::query()->with([
                'category:id,name,slug',
                'addedBy:id,name',
                'updatedBy:id,name'
            ]),
            searchable: [
                'title',
                'slug',
                'short_description',
                'description',
                'location',
                'savings',
            ],
            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'project_status' => [
                    'column' => 'project_status',
                ],
                'is_featured' => [
                    'column' => 'is_featured',
                ],
                'category_id' => [
                    'column' => 'category_id',
                ],
                'site_type' => [
                    'column' => 'site_type',
                ],
            ],
            select: [
                'id',
                'title',
                'slug',
                'short_description',
                'description',
                'location',
                'savings',
                'project_url',
                'start_date',
                'end_date',
                'thumbnail',
                'gallery',
                'category_id',
                'project_status',
                'sort_order',
                'is_featured',
                'site_type',
                'status',
                'added_by',
                'updated_by',
                'created_at',
            ],
        );
    }

    public function findPublicProjectByIdentifier(string $identifier): ?Project
    {
        return Project::query()
            ->with(['category:id,name,slug', 'addedBy:id,name'])
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('slug', $identifier);

                if (is_numeric($identifier)) {
                    $query->orWhere('id', (int) $identifier);
                }
            })
            ->first();
    }
}
