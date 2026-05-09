<?php

namespace App\Http\Services\ProductFeature;

use App\Http\Requests\Products\ProductFeatureCreateRequest;
use App\Http\Services\BaseService;
use App\Models\ProductFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductFeatureService extends BaseService implements ProductFeatureServiceInterface
{
    protected ProductFeatureRepositoryInterface $productFeatureRepository;

    public function __construct(ProductFeatureRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->productFeatureRepository = $repository;
    }

    public function storeOrUpdate(ProductFeatureCreateRequest $request): array
    {
        $editId = $request->edit_id;
        $data = [
            'title' => $request->title,
            'slug' => $this->generateUniqueSlug($request->slug ?: $request->title, $editId ? (int) $editId : null),
            'sub_title' => $request->sub_title,
            'description' => $request->description,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? 1,
        ];

        if ($editId) {
            $item = $this->productFeatureRepository->find((int) $editId);
            if (!$item) {
                return $this->sendResponse(false, __('Data not found'));
            }

            $data['updated_by'] = auth()->id();
            $this->productFeatureRepository->update($item->id, $data);
            $message = __('Product feature updated successfully');
        } else {
            $data['created_by'] = auth()->id();
            $this->productFeatureRepository->createProductFeature($data);
            $message = __('Product feature created successfully');
        }

        return $this->sendResponse(true, $message);
    }

    public function deleteData($id): array
    {
        $item = $this->productFeatureRepository->find((int) $id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->productFeatureRepository->delete((int) $id);

        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publish($id, $status): array
    {
        $item = $this->productFeatureRepository->find((int) $id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->productFeatureRepository->update((int) $id, ['status' => (int) $status]);

        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData(Request $request): array
    {
        return $this->sendResponse(true, __('Data get successfully.'), $this->productFeatureRepository->productFeatureList($request));
    }

    public function editData($id): array
    {
        $item = $this->productFeatureRepository->find((int) $id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

    public function createData(Request $request): array
    {
        return $this->sendResponse(true, '', []);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'product-feature';

        if ($ignoreId) {
            $current = ProductFeature::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'product_features');
    }
}
