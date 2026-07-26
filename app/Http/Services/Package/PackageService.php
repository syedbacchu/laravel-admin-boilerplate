<?php

namespace App\Http\Services\Package;

use App\Http\Requests\Package\PackageCreateRequest;
use App\Http\Services\BaseService;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageService extends BaseService implements PackageServiceInterface
{
    protected PackageRepositoryInterface $packageRepository;

    public function __construct(PackageRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->packageRepository = $repository;
    }

    public function storeOrUpdatePackage(PackageCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'title' => $request->title,
            'slug' => $this->generateUniqueSlug($request->slug ?: $request->title, $editId ? (int) $editId : null),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'thumbnail' => $request->thumbnail,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? 0,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $request->status ?? 1,
            'meta_title' => $request->meta_title ?: $request->title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description ?: $request->short_description,
            'meta_image' => $request->meta_image,
            'site_type' => $request->site_type,
            'package_feature' => $this->normalizePackageFeatures($request->input('package_feature', [])),
        ];

        if ($editId) {
            $item = $this->packageRepository->find($editId);
            if (!$item) {
                return $this->sendResponse(false, __('Data not found'));
            }

            $data['updated_by'] = auth()->id() ?? null;
            $this->packageRepository->update($item->id, $data);
            $item = $this->packageRepository->find($item->id);
            $message = __('Package updated successfully');
        } else {
            $data['added_by'] = auth()->id() ?? null;
            $item = $this->packageRepository->createPackage($data);
            $message = __('Package created successfully');
        }

        return $this->sendResponse(true, $message, $item->fresh());
    }

    public function deletePackage($id): array
    {
        $item = $this->packageRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->packageRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publishPackage($id, $status): array
    {
        $item = $this->packageRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->packageRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->packageRepository->packageList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function packageEditData($id): array
    {
        $item = $this->packageRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

    public function packageCreateData($request): array
    {
        // No category relation for packages; kept for structural parity with Feature module.
        return $this->sendResponse(true, '', []);
    }

    public function getPublicPackageList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->packageRepository->packageList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicPackageDetails(string $identifier): array
    {
        $item = $this->packageRepository->findPublicPackageByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Package not found'), [], 404, __('Package not found'));
        }

        return $this->sendResponse(true, __('Package details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'package';

        if ($ignoreId) {
            $current = Package::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'packages');
    }

    /**
     * Drop empty feature rows coming from the "Add New" repeater, keep only
     * {content, sort_order}, and return a clean, re-indexed array ready to
     * be stored in the package_feature JSON column.
     */
    protected function normalizePackageFeatures($features): array
    {
        if (!is_array($features)) {
            return [];
        }

        return collect($features)
            ->map(fn ($row) => (array) $row)
            ->filter(fn ($row) => filled($row['content'] ?? null))
            ->values()
            ->map(fn ($row, $index) => [
                'content' => $row['content'],
                'sort_order' => isset($row['sort_order']) && $row['sort_order'] !== ''
                    ? (int) $row['sort_order']
                    : $index,
            ])
            ->values()
            ->all();
    }
}