<?php

namespace App\Http\Services\ServiceCategory;

use App\Http\Repositories\BaseRepository;
use App\Models\ServiceCategory;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceCategoryRepository extends BaseRepository implements ServiceCategoryRepositoryInterface
{
    public function __construct(ServiceCategory $model)
    {
        parent::__construct($model);
    }

    public function createServiceCategory(array $data): Model
    {
        return $this->create($data);
    }

    public function serviceCategoryList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: ServiceCategory::query()->with([
                'addedBy:id,name',
                'updatedBy:id,name',
                'services:id,title,category_id'
            ]),
            searchable: [
                'name',
                'slug',
                'description',
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
                'description',
                'image',
                'sort_order',
                'is_featured',
                'status',
                'added_by',
                'updated_by',
                'created_at',
            ],
        );
    }
}
