<?php

namespace App\Http\Services\Products;

use App\Enums\StatusEnum;
use App\Http\Requests\Products\ProductsCreateRequest;
use App\Http\Services\BaseService;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductsService extends BaseService implements ProductsServiceInterface
{
    protected ProductsRepositoryInterface $productsRepository;

    public function __construct(ProductsRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->productsRepository = $repository;
    }

    /*
    |--------------------------------------------------------------------------
    | STORE OR UPDATE
    |--------------------------------------------------------------------------
    */
    public function storeOrUpdate(ProductsCreateRequest $request): array
    {
        $editId = $request->edit_id;

        DB::beginTransaction();

        try {
            $data = [
                // BASIC
                'name'    => $request->name,
                'slug'    => $this->generateUniqueSlug($request->name, $editId),
                'tagline' => $request->tagline,

                // MEDIA
                'image'      => $request->image,
                'gallery'    => $request->gallery ?? null,
                'video_img'  => $request->video_img,
                'video_link' => $request->video_link,

                // PRICING
                'price'         => $request->price,
                'discount'      => $request->discount,
                'discount_type' => $request->discount_type,

                // TAX
                'tax'      => $request->tax ?? 5,
                'tax_type' => $request->tax_type ?? 'percent',

                // RELATIONS
                'brand_id'    => $request->brand_id,
                'category_id' => $request->category_id ?? null, // Primary category for backward compatibility

                // STOCK
                'stock' => $request->stock,
                'sold'  => $request->sold ?? 0,

                // CONTENT
                'short_description'  => $request->short_description,
                'description'        => $request->description,
                'usage_instructions' => $request->usage_instructions,

                // JSON FIELDS
                'attributes'         => $request->attributes ?? null,
                'features'           => $this->normalizeProductFeatures($request->features),
                'quantity_discounts' => $request->quantity_discounts ?? null,

                // SEO
                'meta_title'       => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords'    => $request->meta_keywords,

                // FLAGS
                'is_featured' => $request->is_featured ?? 0,
                'status'      => $request->status ?? StatusEnum::ACTIVE,
            ];

            /*
            |------------------------------------------------------------------
            | CREATE / UPDATE
            |------------------------------------------------------------------
            */
            if ($editId) {
                $data['updated_by'] = auth()->id();
                $this->productsRepository->update($editId, $data);
                $product = $this->productsRepository->find($editId);
            } else {
                $data['created_by'] = auth()->id();
                $product = $this->productsRepository->create($data);
            }

            /*
            |------------------------------------------------------------------
            | VARIATIONS
            |------------------------------------------------------------------
            */
            if (!empty($request->variations)) {

                if ($editId) {
                    $product->variations()->delete();
                }

                foreach ($request->variations as $var) {
                    if (!empty($var['attribute_value_id'])) {
                        $product->variations()->create([
                            'attribute_value_id' => $var['attribute_value_id'],
                            'sku'                => $var['sku'] ?? 'VAR-' . strtoupper(uniqid()),
                            'price'              => $var['price'] ?? $request->price ?? 0,
                            'stock'              => $var['stock'] ?? 0,
                            'status'             => $var['status'] ?? 1,
                        ]);
                    }
                }
            }

            /*
            |------------------------------------------------------------------
            | MULTIPLE CATEGORIES (MANY-TO-MANY)
            |------------------------------------------------------------------
            */
            if (!empty($request->category_ids) && is_array($request->category_ids)) {
                // Remove empty values
                $categoryIds = array_filter($request->category_ids, function ($value) {
                    return !empty($value) && is_numeric($value);
                });

                // Sync categories to pivot table
                $product->categories()->sync($categoryIds);
            } else {
                // If no category_ids provided, remove all mappings
                $product->categories()->sync([]);
            }

            DB::commit();

            return $this->sendResponse(
                true,
                $editId ? __('Updated successfully') : __('Created successfully')
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendResponse(false, $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function deleteData($id): array
    {
        $item = $this->productsRepository->find($id);

        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $item->variations()->delete();
        $this->productsRepository->delete($id);

        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLISH / STATUS TOGGLE
    |--------------------------------------------------------------------------
    */
    public function publish($id, $status): array
    {
        $item = $this->productsRepository->find($id);

        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->productsRepository->update($id, ['status' => (int) $status]);

        return $this->sendResponse(true, __('Status updated successfully'));
    }

    /*
    |--------------------------------------------------------------------------
    | FEATURED TOGGLE
    |--------------------------------------------------------------------------
    */
    public function featured($id, $status): array
    {
        $item = $this->productsRepository->find($id);

        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->productsRepository->update($id, ['is_featured' => (int) $status]);

        return $this->sendResponse(true, __('Featured updated successfully'));
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLE DATA
    |--------------------------------------------------------------------------
    */
    public function getDataTableData($request): array
    {
        $data = $this->productsRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT DATA
    |--------------------------------------------------------------------------
    */
    public function editData($id): array
    {
        $item = $this->productsRepository->find($id);

        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $item->load('variations.attributeValue.attribute', 'categories');

        return $this->sendResponse(true, '', $item);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE DATA (dropdown data)
    |--------------------------------------------------------------------------
    */
    public function createData(Request $request): array
    {
        $attributeValues = AttributeValue::with('attribute')->get();

        return $this->sendResponse(true, '', [
            'attributeValues' => $attributeValues,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLIC LIST
    |--------------------------------------------------------------------------
    */
    public function getPublicList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->productsRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLIC DETAILS
    |--------------------------------------------------------------------------
    */
    public function getPublicDetails(string $identifier): array
    {
        $item = $this->productsRepository->findPublicByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Product not found'), [], 404, __('Product not found'));
        }

        return $this->sendResponse(true, __('Product details'), $item);
    }

    /*
    |--------------------------------------------------------------------------
    | SLUG GENERATOR
    |--------------------------------------------------------------------------
    */
    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'product-';

        if ($ignoreId) {
            $current = Product::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'products');
    }

    protected function normalizeProductFeatures(?array $features): ?array
    {
        $normalized = collect($features ?? [])
            ->map(function ($feature) {
                $items = collect($feature['items'] ?? [])
                    ->map(function ($item) {
                        return [
                            'title' => trim((string) ($item['title'] ?? '')),
                            'sub_title' => trim((string) ($item['sub_title'] ?? '')),
                            'description' => trim((string) ($item['description'] ?? '')),
                            'image' => $item['image'] ?? null,
                            'sort_order' => (int) ($item['sort_order'] ?? 0),
                            'status' => (int) ($item['status'] ?? 1),
                        ];
                    })
                    ->filter(function ($item) {
                        return $item['title'] !== ''
                            || $item['sub_title'] !== ''
                            || $item['description'] !== ''
                            || !empty($item['image']);
                    })
                    ->values()
                    ->all();

                return [
                    'product_feature_id' => !empty($feature['product_feature_id']) ? (int) $feature['product_feature_id'] : null,
                    'feature_title' => trim((string) ($feature['feature_title'] ?? '')),
                    'feature_slug' => trim((string) ($feature['feature_slug'] ?? '')),
                    'feature_sub_title' => trim((string) ($feature['feature_sub_title'] ?? '')),
                    'feature_description' => trim((string) ($feature['feature_description'] ?? '')),
                    'feature_image' => $feature['feature_image'] ?? null,
                    'feature_sort_order' => (int) ($feature['feature_sort_order'] ?? 0),
                    'items' => $items,
                ];
            })
            ->filter(function ($feature) {
                return !empty($feature['feature_title']) || !empty($feature['items']);
            })
            ->values()
            ->all();

        return !empty($normalized) ? $normalized : null;
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLIC LIST
    |--------------------------------------------------------------------------
    */
    public function getHomeProductList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1, 'per_page' => 3 , 'is_featured' => enum(StatusEnum::ACTIVE)]);
        $data = $this->productsRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }
}
