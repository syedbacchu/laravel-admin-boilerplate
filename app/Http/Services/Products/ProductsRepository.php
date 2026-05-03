<?php

namespace App\Http\Services\Products;

use App\Http\Repositories\BaseRepository;
use App\Models\Product;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductsRepository extends BaseRepository implements ProductsRepositoryInterface
{
    public function __construct(Product $model)
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
            query: Product::with('category'), // relation load

            searchable: [
                'name',
                'slug',
            ],

            filters: [
                'status' => [
                    'column' => 'status',
                ],
            ],

            select: [
                'id',
                'category_id',
                'name',
                'slug',
                'image',
                'price',
                'stock',
                'status',
            ],
        );
    }
   public function findPublicByIdentifier(string $identifier): ?Product
    {
        return Product::query()
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier);
            })
            ->first();
    }
}
