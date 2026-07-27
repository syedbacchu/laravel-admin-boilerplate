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
        $query = ProductCategory::query()
            ->with(['parent'])
            ->hasProducts() // Only categories with at least one product
            ->withCount('products as product_count');

        $result = DataListManager::list(
            request: $request,
            query: $query,

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

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | FIND CATEGORY WITH PRODUCTS COUNT
    |--------------------------------------------------------------------------
    */
    public function findCategoryWithProducts(string $id): ?ProductCategory
    {
        return ProductCategory::query()
            ->with(['parent', 'children'])
            ->withCount('products as product_count')
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
