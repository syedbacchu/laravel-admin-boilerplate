<?php

namespace App\Http\Services\Attribute;

use App\Http\Repositories\BaseRepository;
use App\Models\AttributeType;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    public function __construct(AttributeType $model)
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
            query: AttributeType::query(),

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
                'name',
                'icon',
                'status',
                'created_at',
                'updated_at',
            ],
        );
    }
   public function findPublicByIdentifier(string $identifier): ?AttributeType
    {
        return AttributeType::query()
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier);
            })
            ->first();
    }
}
