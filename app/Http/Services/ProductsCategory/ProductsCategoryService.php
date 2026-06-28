<?php

namespace App\Http\Services\ProductsCategory;

use App\Enums\StatusEnum;
use App\Http\Requests\ProductsCategroy\ProductsCategoryCreateRequest;
use App\Http\Services\BaseService;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsCategoryService extends BaseService implements ProductsCategoryServiceInterface
{
    protected ProductsCategoryRepositoryInterface $productsCategoryRepository;

    public function __construct(ProductsCategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->productsCategoryRepository = $repository;
    }

    public function storeOrUpdate(ProductsCategoryCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name, $editId),
            'image' => $request->image,
            'cover_image' => $request->cover_image,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? StatusEnum::ACTIVE,
            'is_featured' => $request->is_featured ?? 0,
            'site_type' => $request->site_type ?? 1,
        ];

        if ($editId) {
            $data['updated_by'] = auth()->id();
            $this->productsCategoryRepository->update($editId, $data);
            return $this->sendResponse(true, 'Updated successfully');
        }

        $data['created_by'] = auth()->id();
        $this->productsCategoryRepository->create($data);

        return $this->sendResponse(true, 'Created successfully');
    }

    public function deleteData($id): array
    {
        $item = $this->productsCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->productsCategoryRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publish($id, $status): array
    {
        $item = $this->productsCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->productsCategoryRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function featured($id, $isFeatured): array
    {
        $item = $this->productsCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->productsCategoryRepository->update($id, ['is_featured' => (bool) $isFeatured]);
        return $this->sendResponse(true, __('Featured status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->productsCategoryRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function editData($id): array
    {
        $item = $this->productsCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

    public function createData(Request $request): array
    {
        $categories = ProductCategory::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return $this->sendResponse(true, '', [
            'categories' => $categories,
        ]);
    }

    public function getPublicList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->productsCategoryRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicDetails(string $identifier): array
    {
        $item = $this->productsCategoryRepository->findPublicByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Products category not found'), [], 404, __('Products category not found'));
        }

        return $this->sendResponse(true, __('Products category details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'products-category';

        if ($ignoreId) {
            $current = ProductCategory::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'product_categories');
    }
}
