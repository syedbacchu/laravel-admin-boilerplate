<?php

namespace App\Http\Controllers\Admin\Stat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stat\StatCreateRequest;
use App\Http\Services\Response\ResponseService;
use App\Http\Services\Stat\StatServiceInterface;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StatController extends Controller
{
    protected StatServiceInterface $stat;

    public function __construct(StatServiceInterface $stat)
    {
        $this->stat = $stat;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Stat List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->stat
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'image' => fn ($item) => $item->image
                    ? '<img src="' . $item->image . '" class="h-12 w-12 rounded object-cover">'
                    : '-',

                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('stat.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('stat.edit', $item->id)),
                            delete_column(route('stat.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['image', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('stat', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->stat->createData($request)['data'];

        $data['pageTitle'] = __('Create Stat');
        $data['function_type'] = 'create';

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('stat', 'create'));
    }

    public function store(StatCreateRequest $request): RedirectResponse
    {
        $response = $this->stat->storeOrUpdate($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'stat.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->stat->editData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $setup = $this->stat->createData(request())['data'];
        $item = $response['data'];

        $data['pageTitle'] = __('Update Stat');
        $data['function_type'] = 'update';
        $data['item'] = $item;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('stat', 'create'));
    }

    public function update(StatCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->stat->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'stat.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->stat->deleteData($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'stat.list');
    }

    public function statStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->stat->publish($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('statStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
