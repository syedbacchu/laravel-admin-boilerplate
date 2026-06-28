<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsCategroy\ProductsCategoryCreateRequest;
use App\Http\Services\ProductsCategory\ProductsCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    protected ProductsCategoryServiceInterface $productCategory;

    public function __construct(ProductsCategoryServiceInterface $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Product Category List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->productCategory
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'image' => fn ($item) => $item->image
                    ? '<img src="' . $item->image . '" class="h-12 w-12 rounded object-cover">'
                    : '-',

                    'parent' => fn ($item) => optional($item->parent)->name ?? '-',
                    'slug' => fn ($item) => $item->slug ?? '-',
                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('product.category.publish'),
                        $item->id,
                        $item->status === 1
                    ),
                    'is_featured_toggle' => fn($item) => toggle_column(
                        route('product.category.featured'),
                        $item->id,
                        $item->is_featured === 1
                    ),
                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('product.category.edit', $item->id)),
                            delete_column(route('product.category.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['image', 'status_toggle','is_featured_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('products_category', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->productCategory->createData($request)['data'];

        $data['pageTitle'] = __('Create Product Category');
        $data['function_type'] = 'create';
        $data['categories'] = $setup['categories'] ?? [];

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('products_category', 'create'));
    }

    public function store(ProductsCategoryCreateRequest $request): RedirectResponse
    {
        $response = $this->productCategory->storeOrUpdate($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'product.category.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->productCategory->editData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $setup = $this->productCategory->createData(request())['data'];
        $item = $response['data'];

        $data['pageTitle'] = __('Update Product Category');
        $data['function_type'] = 'update';
        $data['item'] = $item;
        $data['categories'] = $setup['categories'] ?? [];

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('products_category', 'create'));
    }

    public function update(ProductsCategoryCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->productCategory->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'product.category.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->productCategory->deleteData($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'product.category.list');
    }

    public function productCategoryStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->productCategory->publish($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('productCategoryStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }

    public function featuredCategoryStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->productCategory->featured($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('featuredCategoryStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
