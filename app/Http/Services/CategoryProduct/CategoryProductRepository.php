<?php

namespace App\Http\Services\CategoryProduct;

use App\Http\Repositories\BaseRepository;
use App\Http\Repositories\BaseRepositoryInterface;
use App\Models\ProductCategory;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CategoryProductRepository extends BaseRepository implements CategoryProductRepositoryInterface, BaseRepositoryInterface
{
    public function __construct(ProductCategory $model)
    {
        parent::__construct($model);
    }

    /*
    |--------------------------------------------------------------------------
    | DATA LIST (Categories with at least one product)
    |--------------------------------------------------------------------------
    */
    public function dataList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: ProductCategory::query()
                ->withCount('products') // Load products count
                ->whereHas('products'), // Only categories with at least one product

            searchable: [
                'name',
                'slug',
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
                'cover_image',
                'sort_order',
                'status',
                'is_featured',
                'meta_title',
                'meta_description',
            ],
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FIND CATEGORY WITH PRODUCTS COUNT
    |--------------------------------------------------------------------------
    */
    public function findCategoryWithProducts(string $id): ?ProductCategory
    {
        return ProductCategory::query()
            ->withCount('products')
            ->with(['parent', 'children'])
            ->find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | GET PRODUCTS BY CATEGORY
    |--------------------------------------------------------------------------
    */
    public function getProductsByCategory(string $id): array
    {
        $category = ProductCategory::query()->find($id);

        if (!$category) {
            return [];
        }

        $products = $category->products()
            ->with(['categories', 'variations'])
            ->orderBy('name')
            ->get();

        return $products->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | BASE METHODS
    |--------------------------------------------------------------------------
    */
    public function find(int $id, array $columns = ['*']): ?\Illuminate\Database\Eloquent\Model
    {
        return ProductCategory::query()->select($columns)->find($id);
    }

    public function update(int $id, array $data): bool
    {
        return ProductCategory::query()->where('id', $id)->update($data) > 0;
    }
}