<?php

namespace App\Http\Services\Feature;

use App\Http\Repositories\BaseRepository;
use App\Models\Feature;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FeatureRepository extends BaseRepository implements FeatureRepositoryInterface
{
    public function __construct(Feature $model)
    {
        parent::__construct($model);
    }

    public function createFeature(array $data): Model
    {
        return $this->create($data);
    }

    public function featureList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: Feature::query()->with([
                'category:id,name,slug',
                'addedBy:id,name',
                'updatedBy:id,name'
            ]),
            searchable: [
                'title',
                'slug',
                'short_description',
                'description',
            ],
            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'is_featured' => [
                    'column' => 'is_featured',
                ],
                'category_id' => [
                    'column' => 'category_id',
                ],
            ],
            select: [
                'id',
                'title',
                'slug',
                'short_description',
                'description',
                'thumbnail',
                'image',
                'link',
                'category_id',
                'sort_order',
                'is_featured',
                'status',
                'added_by',
                'updated_by',
                'created_at',
            ],
        );
    }

    public function findPublicFeatureByIdentifier(string $identifier): ?Feature
    {
        return Feature::query()
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
