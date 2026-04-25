<?php

namespace App\Http\Services\ProjectCategory;

use App\Http\Repositories\BaseRepository;
use App\Models\ProjectCategory;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProjectCategoryRepository extends BaseRepository implements ProjectCategoryRepositoryInterface
{
    public function __construct(ProjectCategory $model)
    {
        parent::__construct($model);
    }

    public function createProjectCategory(array $data): Model
    {
        return $this->create($data);
    }

    public function projectCategoryList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: ProjectCategory::query()->with([
                'addedBy:id,name',
                'updatedBy:id,name',
                'projects:id,title,category_id'
            ]),
            searchable: [
                'name',
                'slug',
                'description',
            ],
            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'is_featured' => [
                    'column' => 'is_featured',
                ],
            ],
            select: [
                'id',
                'name',
                'slug',
                'description',
                'icon',
                'image',
                'sort_order',
                'is_featured',
                'status',
                'added_by',
                'updated_by',
                'created_at',
            ],
        );
    }

    public function findPublicProjectCategoryByIdentifier(string $identifier): ?ProjectCategory
    {
        return ProjectCategory::query()
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
