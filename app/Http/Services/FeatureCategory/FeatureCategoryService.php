<?php

namespace App\Http\Services\FeatureCategory;

use App\Http\Requests\Feature\FeatureCategoryCreateRequest;
use App\Http\Services\BaseService;
use App\Models\FeatureCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeatureCategoryService extends BaseService implements FeatureCategoryServiceInterface
{
    protected FeatureCategoryRepositoryInterface $featureCategoryRepository;

    public function __construct(FeatureCategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->featureCategoryRepository = $repository;
    }

    public function storeOrUpdateFeatureCategory(FeatureCategoryCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->slug ?: $request->name, $editId ? (int) $editId : null),
            'description' => $request->description,
            'icon' => $request->icon,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? 0,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $request->status ?? 1,
        ];

        if ($editId) {
            $item = $this->featureCategoryRepository->find($editId);
            if (!$item) {
                return $this->sendResponse(false, __('Data not found'));
            }

            $data['updated_by'] = auth()->id() ?? null;
            $this->featureCategoryRepository->update($item->id, $data);
            $item = $this->featureCategoryRepository->find($item->id);
            $message = __('Feature category updated successfully');
        } else {
            $data['added_by'] = auth()->id() ?? null;
            $item = $this->featureCategoryRepository->createFeatureCategory($data);
            $message = __('Feature category created successfully');
        }

        return $this->sendResponse(true, $message, $item);
    }

    public function deleteFeatureCategory($id): array
    {
        $item = $this->featureCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        if ($item->features()->count() > 0) {
            return $this->sendResponse(false, __('Cannot delete category with existing features'));
        }

        $this->featureCategoryRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publishFeatureCategory($id, $status): array
    {
        $item = $this->featureCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->featureCategoryRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->featureCategoryRepository->featureCategoryList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function featureCategoryEditData($id): array
    {
        $item = $this->featureCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

    public function featureCategoryCreateData($request): array
    {
        return $this->sendResponse(true, '', []);
    }

    public function getPublicFeatureCategoryList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->featureCategoryRepository->featureCategoryList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicFeatureCategoryDetails(string $identifier): array
    {
        $item = $this->featureCategoryRepository->findPublicFeatureCategoryByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Feature category not found'), [], 404, __('Feature category not found'));
        }

        return $this->sendResponse(true, __('Feature category details'), $item->load(['features:id,title,slug,thumbnail,category_id']));
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'feature-category';

        if ($ignoreId) {
            $current = FeatureCategory::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'feature_categories');
    }
}
