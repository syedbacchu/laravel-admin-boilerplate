<?php

namespace App\Http\Services\ProductFeature;

use App\Http\Repositories\BaseRepository;
use App\Models\ProductFeature;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductFeatureRepository extends BaseRepository implements ProductFeatureRepositoryInterface
{
    public function __construct(ProductFeature $model)
    {
        parent::__construct($model);
    }

    public function createProductFeature(array $data): Model
    {
        return $this->create($data);
    }

    public function productFeatureList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: ProductFeature::query(),
            searchable: [
                'title',
                'slug',
                'sub_title',
                'description',
            ],
            filters: [
                'status' => [
                    'column' => 'status',
                ],
            ],
            select: [
                'id',
                'title',
                'slug',
                'sub_title',
                'description',
                'image',
                'sort_order',
                'status',
                'created_at',
            ],
        );
    }

    public function findByIdentifier(string $identifier): ?ProductFeature
    {
        return ProductFeature::query()
            ->where(function ($query) use ($identifier) {
                $query->where('slug', $identifier);

                if (is_numeric($identifier)) {
                    $query->orWhere('id', (int) $identifier);
                }
            })
            ->first();
    }
}
