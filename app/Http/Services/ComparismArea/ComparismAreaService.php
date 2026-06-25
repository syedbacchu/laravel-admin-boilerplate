<?php

namespace App\Http\Services\ComparismArea;

use App\Enums\StatusEnum;
use App\Http\Requests\Comparism\ComparismAreaCreateRequest;
use App\Http\Services\BaseService;
use App\Models\Comparism;
use App\Models\ComparismArea;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComparismAreaService extends BaseService implements ComparismAreaServiceInterface
{
    protected ComparismAreaRepositoryInterface $comparismRepository;

    public function __construct(ComparismAreaRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->comparismRepository = $repository;
    }

    public function storeOrUpdate(ComparismAreaCreateRequest $request): array
    {
        $editId = $request->edit_id;

        if ($editId) {

            $item = $this->comparismRepository->find($editId);

            $item->update([
                'compare_id' => $request->compare_id,
                'left_side'  => $request->left_side[0] ?? '',
                'right_side' => $request->right_side[0] ?? '',
                'sort_order' => (int) ($request->sort_order[0] ?? 0),
                'status'     => $request->status ?? StatusEnum::ACTIVE,
                'updated_by' => auth()->id(),
            ]);

            return $this->sendResponse(true, __('Updated successfully'));
        }

        foreach ($request->left_side as $key => $leftSide) {
            ComparismArea::create([
                'compare_id' => $request->compare_id,
                'left_side'  => $leftSide,
                'right_side' => $request->right_side[$key] ?? '',
                'sort_order' => (int) ($request->sort_order[$key] ?? 0),
                'status'     => $request->status ?? 1,
            ]);
        }

        return $this->sendResponse(true, __('Created successfully'));
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
        $items = Comparism::query()
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        return $this->sendResponse(true, '', [
            'items' => $items,
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
            $current = ComparismArea::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'comparisms');
    }
}
