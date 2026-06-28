<?php

namespace App\Http\Services\ProductsCategory;

use App\Http\Repositories\BaseRepository;
use App\Models\ProductCategory;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductsCategoryRepository extends BaseRepository implements ProductsCategoryRepositoryInterface
{
    public function __construct(ProductCategory $model)
    {
        parent::__construct($model);
    }

    public function dataCreate(array $data): Model
    {
        return $this->create($data);
    }

    public function dataList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: ProductCategory::query(),

            searchable: [
                'name',
            ],

            filters: [
                'status' => [
                    'column' => 'status',
                ],
            ],

            select: [
                'id',
                'parent_id',
                'name',
                'slug',
                'image',
                'sort_order',
                'status',
                'is_featured',
            ],
        );
    }
   public function findPublicByIdentifier(string $identifier): ?ProductCategory
    {
        return ProductCategory::query()
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier);
            })
            ->first();
    }
}
