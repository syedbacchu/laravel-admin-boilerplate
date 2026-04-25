<?php

namespace App\Http\Controllers\Admin\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ServiceCategoryCreateRequest;
use App\Http\Services\ServiceCategory\ServiceCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    protected ServiceCategoryServiceInterface $service;

    public function __construct(ServiceCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Service Category List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->service
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'image' => fn ($item) => $item->image
                        ? '<img src="' . $item->image . '" alt="' . $item->name . '" class="h-12 w-12 object-cover rounded">'
                        : '-',

                    'services_count' => fn ($item) => $item->services->count() ?? 0,

                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('serviceCategory.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('serviceCategory.edit', $item->id)),
                            delete_column(route('serviceCategory.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['image', 'status_toggle', 'actions']
            );
        }

        $viewPath = \App\Http\Services\Response\Viewed::get('serviceCategory', 'list');
        return ResponseService::send([
            'data' => $data,
        ], null, $viewPath);
    }

    public function create(Request $request)
    {
        $setup = $this->service->serviceCategoryCreateData($request)['data'];

        $data['pageTitle'] = __('Create Service Category');
        $data['function_type'] = 'create';

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('serviceCategory', 'create'));
    }

    public function store(ServiceCategoryCreateRequest $request): RedirectResponse
    {
        $response = $this->service->storeOrUpdateServiceCategory($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'serviceCategory.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->service->serviceCategoryEditData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $item = $response['data'];

        $data['pageTitle'] = __('Update Service Category');
        $data['function_type'] = 'update';
        $data['item'] = $item;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('serviceCategory', 'create'));
    }

    public function update(ServiceCategoryCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->service->storeOrUpdateServiceCategory($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'serviceCategory.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->service->deleteServiceCategory($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'serviceCategory.list');
    }

    public function serviceCategoryStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->service->publishServiceCategory($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('serviceCategoryStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
