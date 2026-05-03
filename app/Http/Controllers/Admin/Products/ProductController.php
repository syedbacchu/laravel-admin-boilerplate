<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductsCreateRequest;
use App\Http\Services\Products\ProductsServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Models\AttributeValue;
use App\Models\ProductCategory;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductsServiceInterface $product;

    public function __construct(ProductsServiceInterface $product)
    {
        $this->product = $product;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Product  List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->product
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'image' => fn ($item) => $item->image
                    ? '<img src="' . $item->image . '" class="h-12 w-12 rounded object-cover">'
                    : '-',

                    'category' => fn ($item) => $item->category?->name ?? '-',
                    'slug' => fn ($item) => $item->slug ?? '-',
                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('product.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('product.edit', $item->id)),
                            delete_column(route('product.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['image', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('products', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->product->createData($request)['data'];

        $data['pageTitle'] = __('Create Product ');
        $data['function_type'] = 'create';
        $data['categories'] = ProductCategory::all();
        $data['attributes'] = AttributeValue::all();

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('products', 'create'));
    }

    public function store(ProductsCreateRequest $request): RedirectResponse
    {
        $response = $this->product->storeOrUpdate($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'product.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->product->editData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $setup = $this->product->createData(request())['data'];
        $item = $response['data'];

        $data['pageTitle'] = __('Update Product ');
        $data['function_type'] = 'update';
        $data['item'] = $item;
        $data['categories'] = ProductCategory::all();
        // $data['attributes'] = AttributeValue::all();

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('products', 'create'));
    }

    public function update(ProductsCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->product->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'product.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->product->deleteData($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'product.list');
    }

    public function productStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->product->publish($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('productStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
