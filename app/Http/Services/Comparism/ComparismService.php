<?php

namespace App\Http\Services\Comparism;

use App\Enums\StatusEnum;
use App\Http\Requests\Comparism\ComparismCreateRequest;
use App\Http\Services\BaseService;
use App\Models\Comparism;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComparismService extends BaseService implements ComparismServiceInterface
{
    protected ComparismRepositoryInterface $comparismRepository;

    public function __construct(ComparismRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->comparismRepository = $repository;
    }

    public function storeOrUpdate(ComparismCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'site_type' => $request->site_type ?? 1,
            'area' => $request->area,
            'status' => $request->status ?? StatusEnum::ACTIVE,

        ];

        if ($editId) {
            $data['updated_by'] = auth()->id();
            $this->comparismRepository->update($editId, $data);

            return $this->sendResponse(true, 'Updated successfully');
        }

        $data['created_by'] = auth()->id();
        $this->comparismRepository->create($data);

        return $this->sendResponse(true, 'Created successfully');
    }

    public function deleteData($id): array
    {
        $item = $this->comparismRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->comparismRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publish($id, $status): array
    {
        $item = $this->comparismRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->comparismRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->comparismRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function editData($id): array
    {
        $item = $this->comparismRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

   public function createData(Request $request): array
    {
        $comparisms = Comparism::query()
            ->where('status', 1)
            ->orderBy('area')
            ->get(['id', 'area']);

        return $this->sendResponse(true, '', [
            'comparisms' => $comparisms,
        ]);
    }

    public function getPublicList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->comparismRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicDetails(string $identifier): array
    {
        $item = $this->comparismRepository->findPublicByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Compersim not found'), [], 404, __('Compersim not found'));
        }

        return $this->sendResponse(true, __('Compersim details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'comparism';

        if ($ignoreId) {
            $current = Comparism::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'comparisms');
    }
}
