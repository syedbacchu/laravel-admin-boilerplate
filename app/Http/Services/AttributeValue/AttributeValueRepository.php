<?php

namespace App\Http\Services\AttributeValue;

use App\Http\Repositories\BaseRepository;
use App\Models\AttributeValue;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AttributeValueRepository extends BaseRepository implements AttributeValueRepositoryInterface
{
    public function __construct(AttributeValue $model)
    {
        parent::__construct($model);
    }

    public function dataCreate(array $data): Model
    {
        return $this->create($data);
    }

    public function dataList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: AttributeValue::query(),

             searchable: [
            'name',
            ],

            filters: [
                'status' => [
                    'column' => 'status',
                ],
            ],

            select: [
                'id',
                'type_id',
                'name',
                'value',
                'icon',
                'status',
                'created_at',
            ],
        );
    }
   public function findPublicByIdentifier(string $identifier): ?AttributeValue
    {
        return AttributeValue::query()
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier);
            })
            ->first();
    }
}
