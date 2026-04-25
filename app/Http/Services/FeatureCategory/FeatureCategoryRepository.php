<?php

namespace App\Http\Services\FeatureCategory;

use App\Http\Repositories\BaseRepository;
use App\Models\FeatureCategory;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FeatureCategoryRepository extends BaseRepository implements FeatureCategoryRepositoryInterface
{
    public function __construct(FeatureCategory $model)
    {
        parent::__construct($model);
    }

    public function createFeatureCategory(array $data): Model
    {
        return $this->create($data);
    }

    public function featureCategoryList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: FeatureCategory::query()->with([
                'addedBy:id,name',
                'updatedBy:id,name',
                'features:id,title,category_id'
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

    public function findPublicFeatureCategoryByIdentifier(string $identifier): ?FeatureCategory
    {
        return FeatureCategory::query()
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
