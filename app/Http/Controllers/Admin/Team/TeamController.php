<?php

namespace App\Http\Controllers\Admin\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\TeamCreateRequest;
use App\Http\Services\Response\ResponseService;
use App\Http\Services\Team\TeamServiceInterface;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    protected TeamServiceInterface $team;

    public function __construct(TeamServiceInterface $team)
    {
        $this->team = $team;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Team List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->team
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'image' => fn ($item) => $item->image
                    ? '<img src="' . $item->image . '" class="h-12 w-12 rounded object-cover">'
                    : '-',

                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('team.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('team.edit', $item->id)),
                            delete_column(route('team.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['image', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('team', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->team->createData($request)['data'];

        $data['pageTitle'] = __('Create Team');
        $data['function_type'] = 'create';

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('team', 'create'));
    }

    public function store(TeamCreateRequest $request): RedirectResponse
    {
        $response = $this->team->storeOrUpdate($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'team.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->team->editData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $setup = $this->team->createData(request())['data'];
        $item = $response['data'];

        $data['pageTitle'] = __('Update Team');
        $data['function_type'] = 'update';
        $data['item'] = $item;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('team', 'create'));
    }

    public function update(TeamCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->team->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'team.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->team->deleteData($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'team.list');
    }

    public function teamStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->team->publish($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('teamStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
