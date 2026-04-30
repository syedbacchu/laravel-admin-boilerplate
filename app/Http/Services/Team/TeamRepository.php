<?php

namespace App\Http\Services\Team;

use App\Http\Repositories\BaseRepository;
use App\Models\Team;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{
    public function __construct(Team $model)
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
            query: Team::query(),

            searchable: [
                'name',
                'designation',
                'email',
                'phone',
            ],

            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'is_featured' => [
                    'column' => 'is_featured',
                ],
            ],

            select: [
                'id',
                'name',
                'slug',
                'email',
                'phone',
                'designation',
                'image',
                'cover_image',
                'status',
                'is_featured',
                'site_type',
                'created_by',
                'updated_by',
                'created_at',
            ],
        );
    }
   public function findPublicByIdentifier(string $identifier): ?Team
    {
        return Team::query()
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier);
            })
            ->first();
    }
}
