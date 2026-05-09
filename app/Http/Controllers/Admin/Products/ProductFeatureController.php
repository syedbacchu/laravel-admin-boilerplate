<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductFeatureCreateRequest;
use App\Http\Services\ProductFeature\ProductFeatureServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductFeatureController extends Controller
{
    protected ProductFeatureServiceInterface $service;

    public function __construct(ProductFeatureServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Product Feature List');

        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: fn ($request) => $this->service->getDataTableData($request)['data']['data'],
                columns: [
                    'title' => fn ($item) => $item->title,
                    'slug' => fn ($item) => $item->slug,
                    'sub_title' => fn ($item) => $item->sub_title ?? '-',
                    'image' => fn ($item) => $item->image
                        ? '<img src="' . $item->image . '" class="h-12 w-12 rounded object-cover">'
                        : '<span class="text-gray-400">-</span>',
                    'sort_order' => fn ($item) => $item->sort_order ?? 0,
                    'status_toggle' => fn ($item) => toggle_column(
                        route('product.feature.publish'),
                        $item->id,
                        $item->status === 1
                    ),
                    'actions' => function ($item) {
                        return action_buttons([
                            edit_column(route('product.feature.edit', $item->id)),
                            delete_column(route('product.feature.delete', $item->id)),
                        ]);
                    },
                ],
                rawColumns: ['image', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('product_feature', 'list'));
    }

    public function create(Request $request)
    {
        $data['pageTitle'] = __('Create Product Feature');
        $data['function_type'] = 'create';

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('product_feature', 'create'));
    }

    public function store(ProductFeatureCreateRequest $request): RedirectResponse
    {
        $response = $this->service->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'product.feature.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->service->editData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $data['pageTitle'] = __('Update Product Feature');
        $data['function_type'] = 'update';
        $data['item'] = $response['data'];

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('product_feature', 'create'));
    }

    public function update(ProductFeatureCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->service->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'product.feature.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->service->deleteData($id);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'product.feature.list');
    }

    public function status(Request $request): JsonResponse
    {
        try {
            return response()->json($this->service->publish($request->id, $request->status));
        } catch (\Exception $e) {
            logStore('productFeatureStatus', $e->getMessage());

            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
