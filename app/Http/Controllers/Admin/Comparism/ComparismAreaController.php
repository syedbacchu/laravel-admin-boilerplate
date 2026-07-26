<?php

namespace App\Http\Controllers\Admin\Comparism;

use App\Enums\SliderSiteType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comparism\ComparismAreaCreateRequest;
use App\Http\Services\ComparismArea\ComparismAreaServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ComparismAreaController extends Controller
{
    protected ComparismAreaServiceInterface $comparismArea;

    public function __construct(ComparismAreaServiceInterface $comparismArea)
    {
        $this->comparismArea = $comparismArea;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Comparism Area List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->comparismArea
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'site_type' => function ($item) {
                        return SliderSiteType::getSliderSiteTypeArray()[
                            $item->comparism->site_type ?? ''
                        ] ?? 'N/A';
                    },

                    'left_side' => function ($item) {
                        return $item->left_side;
                    },

                    'right_side' => function ($item) {
                        return $item->right_side;
                    },

                    'status_toggle' => fn ($item) =>
                        toggle_column(
                            route('comparismArea.publish'),
                            $item->id,
                            $item->status === 1
                        ),

                    'actions' => function ($item) {
                        return action_buttons([
                            edit_column(route('comparismArea.edit', $item->id)),
                            delete_column(route('comparismArea.delete', $item->id)),
                        ]);
                    },
                ],
                rawColumns: ['site_type', 'left_side', 'right_side', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send(['data' => $data], null, \App\Http\Services\Response\Viewed::get('comparismArea', 'list'));
    }
    public function create(Request $request)
    {
        $response = $this->comparismArea->createData($request);

        $setup = $response['data'];

        $data = [
            'pageTitle' => __('Create Comparism Area'),
            'function_type' => 'create',
            'items' => $setup['items'] ?? [],
            'comparisms' => $setup['items'] ?? [],
        ];

        return ResponseService::send(
            ['data' => $data],
            null,
            \App\Http\Services\Response\Viewed::get('comparismArea', 'create')
        );
    }

    public function store(ComparismAreaCreateRequest $request): RedirectResponse
    {
        $response = $this->comparismArea->storeOrUpdate($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'comparismArea.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->comparismArea->editData($id);

        if (!$response['success']) {
            return ResponseService::send();
        }

        $setup = $this->comparismArea->createData(request())['data'];

        $data = [
            'pageTitle'     => __('Update Comparism Area'),
            'function_type' => 'update',
            'item'          => $response['data']['item'],
            'areas'         => $response['data']['areas'],
            'items'         => $setup['items'] ?? [],
            'comparisms'    => $setup['items'] ?? [],
        ];

        return ResponseService::send(
            ['data' => $data],
            null,
            \App\Http\Services\Response\Viewed::get('comparismArea', 'create')
        );
    }

    public function update(ComparismAreaCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->comparismArea->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'comparismArea.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->comparismArea->deleteData($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'comparismArea.list');
    }

    public function comparismAreaStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->comparismArea->publish($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('comparismAreaStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
