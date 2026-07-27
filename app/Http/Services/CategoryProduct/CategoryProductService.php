<?php

namespace App\Http\Services\CategoryProduct;

use App\Http\Services\BaseService;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CategoryProductService extends BaseService implements CategoryProductServiceInterface
{
    protected CategoryProductRepositoryInterface $categoryProductRepository;

    public function __construct(CategoryProductRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->categoryProductRepository = $repository;
    }

    /*
    |--------------------------------------------------------------------------
    | GET DATA TABLE DATA (Categories with products)
    |--------------------------------------------------------------------------
    */
    public function getDataTableData($request): array
    {
        $data = $this->categoryProductRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    /*
    |--------------------------------------------------------------------------
    | GET CATEGORY DETAILS WITH PRODUCTS
    |--------------------------------------------------------------------------
    */
    public function getCategoryDetails(string $id): array
    {
        $category = $this->categoryProductRepository->findCategoryWithProducts($id);

        if (!$category) {
            return $this->sendResponse(false, __('Category not found'));
        }

        $products = $this->categoryProductRepository->getProductsByCategory($id);

        return $this->sendResponse(true, __('Category details'), [
            'category' => $category,
            'products' => $products,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLISH / STATUS TOGGLE
    |--------------------------------------------------------------------------
    */
    public function publish($id, $status): array
    {
        $item = $this->categoryProductRepository->find($id);

        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->categoryProductRepository->update($id, ['status' => (int) $status]);

        return $this->sendResponse(true, __('Status updated successfully'));
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH PRODUCTS (For adding to category)
    |--------------------------------------------------------------------------
    */
    public function searchProducts($request): array
    {
        $search = $request->search ?? '';
        $categoryId = $request->category_id;

        $products = \App\Models\Product::query()
            ->where('status', 1)
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            })
            ->whereDoesntHave('categories', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->select(['id', 'name', 'slug', 'image', 'price', 'status'])
            ->limit(20)
            ->get();

        return $this->sendResponse(true, __('Products found'), $products);
    }

    /*
    |--------------------------------------------------------------------------
    | ADD PRODUCT TO CATEGORY
    |--------------------------------------------------------------------------
    */
    public function addProductToCategory($request): array
    {
        $categoryId = $request->category_id;
        $productId = $request->product_id;
        $sortOrder = $request->sort_order ?? 0;

        $category = ProductCategory::query()->find($categoryId);
        if (!$category) {
            return $this->sendResponse(false, __('Category not found'));
        }

        $product = \App\Models\Product::query()->find($productId);
        if (!$product) {
            return $this->sendResponse(false, __('Product not found'));
        }

        // Check if already exists
        $existing = \DB::table('product_category_mappings')
            ->where('category_id', $categoryId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            return $this->sendResponse(false, __('Product already exists in this category'));
        }

        // Add product to category
        \DB::table('product_category_mappings')->insert([
            'category_id' => $categoryId,
            'product_id' => $productId,
            'sort_order' => $sortOrder,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $this->sendResponse(true, __('Product added to category successfully'));
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE PRODUCT FROM CATEGORY
    |--------------------------------------------------------------------------
    */
    public function removeProductFromCategory($request): array
    {
        $categoryId = $request->category_id;
        $productId = $request->product_id;

        \DB::table('product_category_mappings')
            ->where('category_id', $categoryId)
            ->where('product_id', $productId)
            ->delete();

        return $this->sendResponse(true, __('Product removed from category successfully'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PRODUCT SORT ORDER
    |--------------------------------------------------------------------------
    */
    public function updateProductSortOrder($request): array
    {
        $categoryId = $request->category_id;
        $productId = $request->product_id;
        $sortOrder = $request->sort_order ?? 0;

        \DB::table('product_category_mappings')
            ->where('category_id', $categoryId)
            ->where('product_id', $productId)
            ->update([
                'sort_order' => $sortOrder,
                'updated_at' => now(),
            ]);

        return $this->sendResponse(true, __('Sort order updated successfully'));
    }
}