<?php

namespace App\Http\Services\Feature;

use App\Http\Requests\Feature\FeatureCreateRequest;
use App\Http\Services\BaseService;
use App\Models\Feature;
use App\Models\FeatureCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeatureService extends BaseService implements FeatureServiceInterface
{
    protected FeatureRepositoryInterface $featureRepository;

    public function __construct(FeatureRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->featureRepository = $repository;
    }

    public function storeOrUpdateFeature(FeatureCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'title' => $request->title,
            'slug' => $this->generateUniqueSlug($request->slug ?: $request->title, $editId ? (int) $editId : null),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'thumbnail' => $request->thumbnail,
            'image' => $request->image,
            'link' => $request->link,
            'category_id' => $request->category_id ?: null,
            'sort_order' => $request->sort_order ?? 0,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $request->status ?? 1,
            'meta_title' => $request->meta_title ?: $request->title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description ?: $request->short_description,
            'meta_image' => $request->meta_image,
        ];

        if ($editId) {
            $item = $this->featureRepository->find($editId);
            if (!$item) {
                return $this->sendResponse(false, __('Data not found'));
            }

            $data['updated_by'] = auth()->id() ?? null;
            $this->featureRepository->update($item->id, $data);
            $item = $this->featureRepository->find($item->id);
            $message = __('Feature updated successfully');
        } else {
            $data['added_by'] = auth()->id() ?? null;
            $item = $this->featureRepository->createFeature($data);
            $message = __('Feature created successfully');
        }

        return $this->sendResponse(true, $message, $item->fresh(['category']));
    }

    public function deleteFeature($id): array
    {
        $item = $this->featureRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->featureRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publishFeature($id, $status): array
    {
        $item = $this->featureRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->featureRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->featureRepository->featureList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function featureEditData($id): array
    {
        $item = $this->featureRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item->load(['category:id,name']));
    }

    public function featureCreateData($request): array
    {
        $categories = FeatureCategory::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return $this->sendResponse(true, '', [
            'categories' => $categories,
        ]);
    }

    public function getPublicFeatureList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->featureRepository->featureList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicFeatureDetails(string $identifier): array
    {
        $item = $this->featureRepository->findPublicFeatureByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Feature not found'), [], 404, __('Feature not found'));
        }

        return $this->sendResponse(true, __('Feature details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'feature';

        if ($ignoreId) {
            $current = Feature::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'features');
    }
}
