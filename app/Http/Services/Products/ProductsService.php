<?php

namespace App\Http\Services\Products;

use App\Enums\StatusEnum;
use App\Http\Requests\Products\ProductsCreateRequest;
use App\Http\Services\BaseService;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Service;
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

    public function storeOrUpdate(ProductsCreateRequest $request): array
    {
        $editId = $request->edit_id;

        DB::beginTransaction();

        try {

            $data = [
                // BASIC
                'name' => $request->name,
                'slug' => $this->generateUniqueSlug($request->name, $editId),
                'tagline' => $request->tagline,

                // MEDIA
                'image' => $request->image,
                'gallery' => $request->gallery ? json_encode($request->gallery) : null,
                'video_img' => $request->video_img,
                'video_link' => $request->video_link,

                // PRICE
                'price' => $request->price,
                'discount' => $request->discount,
                'discount_type' => $request->discount_type,

                // TAX
                'tax' => $request->tax ?? 5,
                'tax_type' => $request->tax_type ?? 'percent',

                // RELATION
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,

                // STOCK
                'stock' => $request->stock,
                'sold' => $request->sold ?? 0,

                // CONTENT
                'short_description' => $request->short_description,
                'description' => $request->description,
                'usage_instructions' => $request->usage_instructions,

                // JSON
                'attributes' => $request->attributes ? json_encode($request->attributes) : null,
                'features' => $request->features ? json_encode($request->features) : null,
                'quantity_discounts' => $request->quantity_discounts ? json_encode($request->quantity_discounts) : null,

                // SEO
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,

                // FLAGS
                'is_featured' => $request->is_featured ?? 0,
                'status' => $request->status ?? StatusEnum::ACTIVE,
            ];

            /*
            |----------------------------------------------------------------------
            | CREATE / UPDATE PRODUCT
            |----------------------------------------------------------------------
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
            |----------------------------------------------------------------------
            | VARIATIONS SAVE (FIXED + SKU)
            |----------------------------------------------------------------------
            */
            if (!empty($request->variations)) {

                if ($editId) {
                    $product->variations()->delete();
                }

                foreach ($request->variations as $var) {

                    if (!empty($var['name'])) {

                        $product->variations()->create([
                            'attribute_value_id' => $var['attribute_value_id'] ?? null,
                            'name' => $var['name'],
                            'sku' => $var['sku'] ?? 'VAR-' . strtoupper(uniqid()),
                            'price' => $var['price'] ?? $request->price ?? 0,
                            'stock' => $var['stock'] ?? 0,
                            'attributes' => !empty($var['attributes'])
                                ? json_encode($var['attributes'])
                                : json_encode([]),

                            'status' => $var['status'] ?? 1,
                        ]);
                    }
                }
            }

            DB::commit();

            return $this->sendResponse(
                true,
                $editId ? 'Updated successfully' : 'Created successfully'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->sendResponse(false, $e->getMessage());
        }
    }

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

    public function publish($id, $status): array
    {
        $item = $this->productsRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->productsRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->productsRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function editData($id): array
    {
        $item = $this->productsRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }
        $item->load('variations');
        return $this->sendResponse(true, '', $item);
    }

    public function createData(Request $request): array
    {
        $categories = Product::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
        $attributeValues = AttributeValue::with('attribute')->get();
        return $this->sendResponse(true, '', [
            'categories' => $categories,
            'attributeValues' => $attributeValues,
        ]);
    }

    public function getPublicList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->productsRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicDetails(string $identifier): array
    {
        $item = $this->productsRepository->findPublicByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Products  not found'), [], 404, __('Products  not found'));
        }

        return $this->sendResponse(true, __('Products  details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'products-';

        if ($ignoreId) {
            $current = Product::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'product');
    }
}
