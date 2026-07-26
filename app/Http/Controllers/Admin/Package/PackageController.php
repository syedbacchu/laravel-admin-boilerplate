<?php

namespace App\Http\Controllers\Admin\Package;

use App\Http\Controllers\Controller;
use App\Http\Requests\Package\PackageCreateRequest;
use App\Http\Services\Package\PackageServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    protected PackageServiceInterface $service;

    public function __construct(PackageServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Package List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->service
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'title' => fn ($item) => $item->title,
                    'slug' => fn ($item) => $item->slug,
                    'thumbnail' => fn ($item) => $item->thumbnail
                        ? '<img src="' . $item->thumbnail . '" alt="' . $item->title . '" class="h-12 w-12 object-cover rounded">'
                        : '-',
                    'image' => fn ($item) => $item->image
                        ? '<img src="' . $item->image . '" alt="' . $item->title . '" class="h-12 w-12 object-cover rounded">'
                        : '-',
                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('package.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('package.edit', $item->id)),
                            delete_column(route('package.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['thumbnail', 'image', 'link', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('package', 'list'));
    }

    public function create(Request $request)
    {
        $this->service->packageCreateData($request);

        $data['pageTitle'] = __('Create Package');
        $data['function_type'] = 'create';

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('package', 'create'));
    }

    public function store(PackageCreateRequest $request): RedirectResponse
    {
        $response = $this->service->storeOrUpdatePackage($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'package.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->service->packageEditData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $item = $response['data'];

        $data['pageTitle'] = __('Update Package');
        $data['function_type'] = 'update';
        $data['item'] = $item;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('package', 'create'));
    }

    public function update(PackageCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->service->storeOrUpdatePackage($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'package.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->service->deletePackage($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'package.list');
    }

    public function packageStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->service->publishPackage($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('packageStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}