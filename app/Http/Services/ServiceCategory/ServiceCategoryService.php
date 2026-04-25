<?php

namespace App\Http\Services\ServiceCategory;

use App\Http\Requests\Service\ServiceCategoryCreateRequest;
use App\Http\Services\BaseService;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;

class ServiceCategoryService extends BaseService implements ServiceCategoryServiceInterface
{
    protected ServiceCategoryRepositoryInterface $serviceCategoryRepository;

    public function __construct(ServiceCategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->serviceCategoryRepository = $repository;
    }

    public function storeOrUpdateServiceCategory(ServiceCategoryCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->slug ?: $request->name, $editId ? (int) $editId : null),
            'description' => $request->description,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? 0,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $request->status ?? 1,
        ];

        if ($editId) {
            $item = $this->serviceCategoryRepository->find($editId);
            if (!$item) {
                return $this->sendResponse(false, __('Data not found'));
            }

            $data['updated_by'] = auth()->id() ?? null;
            $this->serviceCategoryRepository->update($item->id, $data);
            $item = $this->serviceCategoryRepository->find($item->id);
            $message = __('Service category updated successfully');
        } else {
            $data['added_by'] = auth()->id() ?? null;
            $item = $this->serviceCategoryRepository->createServiceCategory($data);
            $message = __('Service category created successfully');
        }

        return $this->sendResponse(true, $message, $item);
    }

    public function deleteServiceCategory($id): array
    {
        $item = $this->serviceCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        // Check if category has services
        if ($item->services()->count() > 0) {
            return $this->sendResponse(false, __('Cannot delete category with existing services'));
        }

        $this->serviceCategoryRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publishServiceCategory($id, $status): array
    {
        $item = $this->serviceCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->serviceCategoryRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->serviceCategoryRepository->serviceCategoryList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function serviceCategoryEditData($id): array
    {
        $item = $this->serviceCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

    public function serviceCategoryCreateData($request): array
    {
        return $this->sendResponse(true, '', []);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'service-category';

        if ($ignoreId) {
            $current = ServiceCategory::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'service_categories');
    }
}
