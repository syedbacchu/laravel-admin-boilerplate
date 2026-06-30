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
        $compareId = $request->compare_id;
        $areaIds   = $request->area_ids ?? [];
        $isEdit    = collect($areaIds)->filter()->isNotEmpty();

        $keptIds = [];

        foreach ($request->left_side as $key => $leftSide) {
            $rowId = $areaIds[$key] ?? null;

            $payload = [
                'compare_id' => $compareId,
                'left_side'  => $leftSide,
                'right_side' => $request->right_side[$key] ?? '',
                'sort_order' => (int) ($request->sort_order[$key] ?? 0),
                'status'     => $request->status ?? StatusEnum::ACTIVE,
            ];

            if ($rowId) {
                $row = $this->comparismRepository->find($rowId);
                if ($row) {
                    $row->update($payload + ['updated_by' => auth()->id()]);
                    $keptIds[] = $row->id;
                    continue;
                }
            }

            $new = ComparismArea::create($payload);
            $keptIds[] = $new->id;
        }

        // form e thakte na thakte (user delete kore disile) ai compare_id er baki row gula delete
        ComparismArea::query()
            ->where('compare_id', $compareId)
            ->whereNotIn('id', $keptIds)
            ->delete();

        return $this->sendResponse(true, $isEdit ? __('Updated successfully') : __('Created successfully'));
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

        $areas = ComparismArea::query()
            ->where('compare_id', $item->compare_id)
            ->orderBy('sort_order')
            ->get();

        return $this->sendResponse(true, '', [
            'item'  => $item,
            'areas' => $areas,
        ]);
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
