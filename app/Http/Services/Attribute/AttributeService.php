<?php

namespace App\Http\Services\Attribute;

use App\Enums\StatusEnum;
use App\Http\Requests\Attribute\AttributeCreateRequest;
use App\Http\Services\BaseService;
use App\Models\AttributeType;
use App\Models\AttributeValue;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeService extends BaseService implements AttributeServiceInterface
{
    protected AttributeRepositoryInterface $attributeRepository;

    public function __construct(AttributeRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->attributeRepository = $repository;
    }

    public function storeOrUpdate(AttributeCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'name' => $request->name,
            'icon' => $request->icon,
            'status' => $request->status ?? StatusEnum::ACTIVE,
        ];

        if ($editId) {

            // ✅ UPDATE ATTRIBUTE
            $this->attributeRepository->update($editId, $data);

            $attributeId = $editId;

            // 🔥 IMPORTANT: DELETE OLD VALUES FIRST
            AttributeValue::where('type_id', $attributeId)->delete();

        } else {

            // ✅ CREATE ATTRIBUTE
            $attribute = $this->attributeRepository->create($data);
            $attributeId = $attribute->id;
        }

        // ✅ SAVE VALUES (fresh insert)
        if ($request->values && is_array($request->values)) {

            foreach ($request->values as $val) {

                if (!empty($val['name'])) {
                    AttributeValue::create([
                        'type_id' => $attributeId,
                        'name' => $val['name'],
                        'value' => $val['value'] ?? $val['name'],
                        'status' => 1
                    ]);
                }
            }
        }

        return $this->sendResponse(true, $editId ? 'Updated successfully' : 'Created successfully');
    }

    public function deleteData($id): array
    {
        $item = $this->attributeRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->attributeRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publish($id, $status): array
    {
        $item = $this->attributeRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->attributeRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->attributeRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function editData($id): array
    {
        $item = $this->attributeRepository->find($id);
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
        $data = $this->attributeRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicDetails(string $identifier): array
    {
        $item = $this->attributeRepository->findPublicByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Attribute not found'), [], 404, __('Attribute not found'));
        }

        return $this->sendResponse(true, __('Attribute details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'attribute';

        if ($ignoreId) {
            $current = AttributeType::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'attributes');
    }
}
