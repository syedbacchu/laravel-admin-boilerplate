<?php

namespace App\Http\Services\Stat;

use App\Http\Repositories\BaseRepository;
use App\Models\Stat;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StatRepository extends BaseRepository implements StatRepositoryInterface
{
    public function __construct(Stat $model)
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
            query: Stat::query(),

            searchable: [
                'title',
                'subtitle',
            ],

            filters: [
                'status' => [
                    'column' => 'status',
                ],
            ],

            select: [
                'id',
                'image',
                'title',
                'subtitle',
                'description',
                'link',
                'sort_order',
                'status',
                'added_by',
                'updated_by',
                'site_type',
            ],
        );
    }
   public function findPublicByIdentifier(string $identifier): ?Stat
    {
        return Stat::query()
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier);
            })
            ->first();
    }
}
