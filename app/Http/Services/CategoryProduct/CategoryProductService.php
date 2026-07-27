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
}