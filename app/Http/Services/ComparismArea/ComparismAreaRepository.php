<?php

namespace App\Http\Services\ComparismArea;

use App\Http\Repositories\BaseRepository;
use App\Models\ComparismArea;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ComparismAreaRepository extends BaseRepository implements ComparismAreaRepositoryInterface
{
    public function __construct(ComparismArea $model)
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
            query: ComparismArea::query()->with('comparism'),

            searchable: [
                'left_side',
                'right_side',
            ],

            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'compare_id' => [
                    'column' => 'compare_id',
                ],
            ],

            select: [
                'id',
                'compare_id',
                'left_side',
                'right_side',
                'sort_order',
                'status',
            ],
        );
    }
    public function findPublicByIdentifier(string $identifier): ?ComparismArea
    {
        return ComparismArea::query()
            ->where('status', 1)
            ->find($identifier);
    }
}
