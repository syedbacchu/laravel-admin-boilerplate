<?php

namespace App\Http\Services\Stat;

use App\Http\Requests\Stat\StatCreateRequest;
use App\Http\Services\BaseService;
use App\Models\Stat;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StatService extends BaseService implements StatServiceInterface
{
    protected StatRepositoryInterface $statRepository;

    public function __construct(StatRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->statRepository = $repository;
    }

    public function storeOrUpdate(StatCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'link' => $request->link,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? 1,
        ];

        if ($editId) {
            $data['updated_by'] = auth()->id();
            $this->statRepository->update($editId, $data);
            return $this->sendResponse(true, 'Updated successfully');
        }

        $data['created_by'] = auth()->id();
        $this->statRepository->create($data);

        return $this->sendResponse(true, 'Created successfully');
    }

    public function deleteData($id): array
    {
        $item = $this->statRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->statRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publish($id, $status): array
    {
        $item = $this->statRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->statRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->statRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function editData($id): array
    {
        $item = $this->statRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

    public function createData(Request $request): array
    {
        $categories = ServiceCategory::query()
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
        $data = $this->statRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicDetails(string $identifier): array
    {
        $item = $this->statRepository->findPublicByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Stat not found'), [], 404, __('Stat not found'));
        }

        return $this->sendResponse(true, __('Stat details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'stat';

        if ($ignoreId) {
            $current = Stat::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'stats');
    }
}
