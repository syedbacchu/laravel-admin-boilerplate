<?php

namespace App\Http\Controllers\Admin\Comparism;

use App\Enums\SliderSiteType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comparism\ComparismCreateRequest;
use App\Http\Services\Comparism\ComparismServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ComparismController extends Controller
{
    protected ComparismServiceInterface $comparism;

    public function __construct(ComparismServiceInterface $comparism)
    {
        $this->comparism = $comparism;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Comparism List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->comparism
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'site_type' => function ($item) {
                        return SliderSiteType::getSliderSiteTypeArray()[$item->site_type]
                            ?? 'N/A';
                    },
                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('comparism.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('comparism.edit', $item->id)),
                            delete_column(route('comparism.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: [ 'site_type','status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('comparism', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->comparism->createData($request)['data'];

        $data = [
            'pageTitle' => __('Create Comparism'),
            'function_type' => 'create',
        ];

        $data = array_merge($data, $setup);

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('comparism', 'create'));
    }

    public function store(ComparismCreateRequest $request): RedirectResponse
    {
        $response = $this->comparism->storeOrUpdate($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'comparism.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->comparism->editData($id);

        if (!$response['success']) {
            return ResponseService::send();
        }

        $setup = $this->comparism->createData(request())['data'];
        $item = $response['data'];

        $data = [
            'pageTitle'     => __('Update Comparism'),
            'function_type' => 'update',
            'item'          => $item,
        ];

        $data = array_merge($data, $setup);

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('comparism', 'create'));
    }

    public function update(ComparismCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->comparism->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'comparism.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->comparism->deleteData($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'comparism.list');
    }

    public function comparismStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->comparism->publish($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('comparismStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
