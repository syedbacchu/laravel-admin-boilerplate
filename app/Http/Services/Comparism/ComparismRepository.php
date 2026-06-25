<?php

namespace App\Http\Services\Comparism;

use App\Http\Repositories\BaseRepository;
use App\Models\Comparism;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ComparismRepository extends BaseRepository implements ComparismRepositoryInterface
{
    public function __construct(Comparism $model)
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
            query: Comparism::query(),

            searchable: [
                'area',
            ],

            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'site_type' => [
                    'column' => 'site_type',
                ],
            ],

            select: [
                'id',
                'site_type',
                'area',
                'status',
                'created_at',
            ],
        );
    }
   public function findPublicByIdentifier(string $identifier): ?Comparism
    {
        return Comparism::query()
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier)
                    ->orWhere('area', $identifier);
            })
            ->first();
    }
}
