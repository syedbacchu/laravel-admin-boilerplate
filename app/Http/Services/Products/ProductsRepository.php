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
            query: Product::with('category', 'categories', 'variations.attributeValue.attribute'),

            searchable: [
                'name',
                'slug',
                'short_description',
                'description',
            ],

            filters: [
                'status' => [
                    'column' => 'status',
                    'type' => 'basic',
                ],
                'is_featured' => [
                    'column' => 'is_featured',
                    'type' => 'basic',
                ],

                'site_type' => [
                    'column' => 'site_type',
                    'type' => 'basic',
                ],
                'category_id' => [
                    'column' => 'category_id',
                    'type' => 'basic',
                ],
                'brand_id' => [
                    'column' => 'brand_id',
                    'type' => 'basic',
                ],
                'min_price' => [
                    'column' => 'price',
                    'type' => 'basic',
                    'operator' => '>=',
                ],
                'max_price' => [
                    'column' => 'price',
                    'type' => 'basic',
                    'operator' => '<=',
                ],
                'min_stock' => [
                    'column' => 'stock',
                    'type' => 'basic',
                    'operator' => '>=',
                ],
                'max_stock' => [
                    'column' => 'stock',
                    'type' => 'basic',
                    'operator' => '<=',
                ],
                'in_stock' => [
                    'column' => 'stock',
                    'type' => 'basic',
                    'operator' => '>',
                    'value' => 0,
                ],
            ],

            select: [
                'id',
                'name',
                'short_description',
                'slug',
                'tagline',
                'image',
                'price',
                'minimum_quantity',
                'discount',
                'discount_type',
                'stock',
                'sold',
                'is_featured',
                'status',
                'features',
                'category_id',
                'created_at',
            ],
        );
    }
   public function findPublicByIdentifier(string $identifier): ?Product
   {
       return Product::query()
           ->with('category', 'categories', 'variations.attributeValue.attribute')
           ->where('status', 1)
           ->where(function ($query) use ($identifier) {
               $query->where('id', $identifier)
                     ->orWhere('slug', $identifier);
           })
           ->first();
   }
}
