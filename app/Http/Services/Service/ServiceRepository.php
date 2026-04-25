<?php

namespace App\Http\Services\Service;

use App\Http\Repositories\BaseRepository;
use App\Models\Service;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceRepository extends BaseRepository implements ServiceRepositoryInterface
{
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }

    public function createService(array $data): Model
    {
        return $this->create($data);
    }

    public function serviceList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: Service::query()->with([
                'category:id,name,slug',
                'addedBy:id,name',
                'updatedBy:id,name',
            ]),
            searchable: [
                'title',
                'slug',
                'short_description',
                'description',
            ],
            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'is_featured' => [
                    'column' => 'is_featured',
                ],
                'category_id' => [
                    'column' => 'category_id',
                ],
            ],
            select: [
                'id',
                'title',
                'slug',
                'short_description',
                'description',
                'thumbnail',
                'image',
                'category_id',
                'sort_order',
                'is_featured',
                'status',
                'added_by',
                'updated_by',
                'created_at',
            ],
        );
    }

    public function findPublicServiceByIdentifier(string $identifier): ?Service
    {
        return Service::query()
            ->with(['category:id,name,slug', 'addedBy:id,name'])
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('slug', $identifier);

                if (is_numeric($identifier)) {
                    $query->orWhere('id', (int) $identifier);
                }
            })
            ->first();
    }
}
