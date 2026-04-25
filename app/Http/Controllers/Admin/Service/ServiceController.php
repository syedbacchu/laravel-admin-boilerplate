<?php

namespace App\Http\Controllers\Admin\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ServiceCreateRequest;
use App\Http\Services\Service\ServiceServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected ServiceServiceInterface $service;

    public function __construct(ServiceServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Service List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->service
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'category' => fn ($item) => $item->category?->name ?: '-',
                    'thumbnail' => fn ($item) => $item->thumbnail
                        ? '<img src="' . $item->thumbnail . '" alt="' . $item->title . '" class="h-12 w-12 object-cover rounded">'
                        : '-',

                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('service.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('service.edit', $item->id)),
                            delete_column(route('service.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['thumbnail', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('service', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->service->serviceCreateData($request)['data'];

        $data['pageTitle'] = __('Create Service');
        $data['function_type'] = 'create';
        $data['categories'] = $setup['categories'];

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('service', 'create'));
    }

    public function store(ServiceCreateRequest $request): RedirectResponse
    {
        $response = $this->service->storeOrUpdateService($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'service.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->service->serviceEditData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $setup = $this->service->serviceCreateData(request())['data'];
        $item = $response['data'];

        $data['pageTitle'] = __('Update Service');
        $data['function_type'] = 'update';
        $data['item'] = $item;
        $data['categories'] = $setup['categories'];
        $data['selectedCategoryId'] = $item->category_id;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('service', 'create'));
    }

    public function update(ServiceCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->service->storeOrUpdateService($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'service.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->service->deleteService($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'service.list');
    }

    public function serviceStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->service->publishService($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('serviceStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
