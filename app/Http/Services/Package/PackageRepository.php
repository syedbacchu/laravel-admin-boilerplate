<?php

namespace App\Http\Services\Package;

use App\Http\Repositories\BaseRepository;
use App\Models\Package;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PackageRepository extends BaseRepository implements PackageRepositoryInterface
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }

    public function createPackage(array $data): Model
    {
        return $this->create($data);
    }

    public function packageList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: Package::query()->with([
                'addedBy:id,name',
                'updatedBy:id,name'
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
                'site_type' => [
                    'column' => 'site_type',
                ],
                'is_featured' => [
                    'column' => 'is_featured',
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
                'sort_order',
                'is_featured',
                'status',
                'added_by',
                'updated_by',
                'created_at',
                'site_type',
                'package_feature',
            ],
        );
    }

    public function findPublicPackageByIdentifier(string $identifier): ?Package
    {
        return Package::query()
            ->with(['addedBy:id,name'])
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