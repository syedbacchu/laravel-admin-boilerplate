<?php

namespace App\Http\Services\AttributeValue;

use App\Enums\StatusEnum;
use App\Http\Requests\Attribute\AttributeValueCreateRequest;
use App\Http\Services\BaseService;
use App\Models\AttributeType;
use App\Models\AttributeValue;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeValueService extends BaseService implements AttributeValueServiceInterface
{
    protected AttributeValueRepositoryInterface $attributeValueRepository;

    public function __construct(AttributeValueRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->attributeValueRepository = $repository;
    }

    public function storeOrUpdate(AttributeValueCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'type_id' => $request->type_id,
            'name' => $request->name,
            'icon' => $request->icon,
            'value' => $request->value,
            'status' => $request->status ?? StatusEnum::ACTIVE,
        ];

        if ($editId) {
            $data['updated_by'] = auth()->id();
            $this->attributeValueRepository->update($editId, $data);
            return $this->sendResponse(true, 'Updated successfully');
        }

        $data['created_by'] = auth()->id();
        $this->attributeValueRepository->create($data);

        return $this->sendResponse(true, 'Created successfully');
    }

    public function deleteData($id): array
    {
        $item = $this->attributeValueRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->attributeValueRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publish($id, $status): array
    {
        $item = $this->attributeValueRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->attributeValueRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->attributeValueRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function editData($id): array
    {
        $item = $this->attributeValueRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

    public function createData(Request $request): array
    {
        $attributes = AttributeType::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return $this->sendResponse(true, '', [
            'attributes' => $attributes,
        ]);
    }

    public function getPublicList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->attributeValueRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicDetails(string $identifier): array
    {
        $item = $this->attributeValueRepository->findPublicByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Attribute not found'), [], 404, __('Attribute not found'));
        }

        return $this->sendResponse(true, __('Attribute details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'attribute';

        if ($ignoreId) {
            $current = AttributeValue::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'attributes');
    }
}
