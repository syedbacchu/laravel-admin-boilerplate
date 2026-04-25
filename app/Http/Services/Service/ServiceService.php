<?php

namespace App\Http\Services\Service;

use App\Http\Requests\Service\ServiceCreateRequest;
use App\Http\Services\BaseService;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceService extends BaseService implements ServiceServiceInterface
{
    protected ServiceRepositoryInterface $serviceRepository;

    public function __construct(ServiceRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->serviceRepository = $repository;
    }

    public function storeOrUpdateService(ServiceCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'title' => $request->title,
            'slug' => $this->generateUniqueSlug($request->slug ?: $request->title, $editId ? (int) $editId : null),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'thumbnail' => $request->thumbnail,
            'image' => $request->image,
            'category_id' => $request->category_id ?: null,
            'sort_order' => $request->sort_order ?? 0,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $request->status ?? 1,
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'meta_image' => $request->meta_image,
        ];

        if ($editId) {
            $item = $this->serviceRepository->find($editId);
            if (!$item) {
                return $this->sendResponse(false, __('Data not found'));
            }

            $data['updated_by'] = auth()->id() ?? null;
            $this->serviceRepository->update($item->id, $data);
            $item = $this->serviceRepository->find($item->id);
            $message = __('Service updated successfully');
        } else {
            $data['added_by'] = auth()->id() ?? null;
            $item = $this->serviceRepository->createService($data);
            $message = __('Service created successfully');
        }

        return $this->sendResponse(true, $message, $item->fresh(['category']));
    }

    public function deleteService($id): array
    {
        $item = $this->serviceRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->serviceRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publishService($id, $status): array
    {
        $item = $this->serviceRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->serviceRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->serviceRepository->serviceList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function serviceEditData($id): array
    {
        $item = $this->serviceRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item->load(['category:id,name']));
    }

    public function serviceCreateData($request): array
    {
        $categories = ServiceCategory::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return $this->sendResponse(true, '', [
            'categories' => $categories,
        ]);
    }

    public function getPublicServiceList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->serviceRepository->serviceList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicServiceDetails(string $identifier): array
    {
        $item = $this->serviceRepository->findPublicServiceByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Service not found'), [], 404, __('Service not found'));
        }

        return $this->sendResponse(true, __('Service details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'service';

        if ($ignoreId) {
            $current = Service::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'services');
    }
}
