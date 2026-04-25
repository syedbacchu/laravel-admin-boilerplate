<?php

namespace App\Http\Controllers\Admin\Feature;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feature\FeatureCategoryCreateRequest;
use App\Http\Services\FeatureCategory\FeatureCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FeatureCategoryController extends Controller
{
    protected FeatureCategoryServiceInterface $service;

    public function __construct(FeatureCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Feature Category List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->service
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'name' => fn ($item) => $item->name,
                    'slug' => fn ($item) => $item->slug,
                    'icon' => fn ($item) => $item->icon
                        ? '<i class="' . $item->icon . ' text-2xl"></i>'
                        : '-',

                    'image' => fn ($item) => $item->image
                        ? '<img src="' . $item->image . '" alt="' . $item->name . '" class="h-12 w-12 object-cover rounded">'
                        : '-',

                    'features_count' => fn ($item) => $item->features->count() ?? 0,
                    'sort_order' => fn ($item) => $item->sort_order,

                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('featureCategory.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('featureCategory.edit', $item->id)),
                            delete_column(route('featureCategory.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['icon', 'image', 'status_toggle', 'actions']
            );
        }

        $viewPath = \App\Http\Services\Response\Viewed::get('featureCategory', 'list');
        return ResponseService::send([
            'data' => $data,
        ], null, $viewPath);
    }

    public function create(Request $request)
    {
        $setup = $this->service->featureCategoryCreateData($request)['data'];

        $data['pageTitle'] = __('Create Feature Category');
        $data['function_type'] = 'create';

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('featureCategory', 'create'));
    }

    public function store(FeatureCategoryCreateRequest $request): RedirectResponse
    {
        $response = $this->service->storeOrUpdateFeatureCategory($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'featureCategory.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->service->featureCategoryEditData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $item = $response['data'];

        $data['pageTitle'] = __('Update Feature Category');
        $data['function_type'] = 'update';
        $data['item'] = $item;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('featureCategory', 'create'));
    }

    public function update(FeatureCategoryCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->service->storeOrUpdateFeatureCategory($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'featureCategory.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->service->deleteFeatureCategory($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'featureCategory.list');
    }

    public function featureCategoryStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->service->publishFeatureCategory($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('featureCategoryStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
