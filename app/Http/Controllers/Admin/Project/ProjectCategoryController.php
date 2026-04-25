<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectCategoryCreateRequest;
use App\Http\Services\ProjectCategory\ProjectCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectCategoryController extends Controller
{
    protected ProjectCategoryServiceInterface $service;

    public function __construct(ProjectCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Project Category List');
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

                    'projects_count' => fn ($item) => $item->projects->count() ?? 0,
                    'sort_order' => fn ($item) => $item->sort_order,

                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('projectCategory.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('projectCategory.edit', $item->id)),
                            delete_column(route('projectCategory.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['icon', 'image', 'status_toggle', 'actions']
            );
        }

        $viewPath = \App\Http\Services\Response\Viewed::get('projectCategory', 'list');
        return ResponseService::send([
            'data' => $data,
        ], null, $viewPath);
    }

    public function create(Request $request)
    {
        $setup = $this->service->projectCategoryCreateData($request)['data'];

        $data['pageTitle'] = __('Create Project Category');
        $data['function_type'] = 'create';

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('projectCategory', 'create'));
    }

    public function store(ProjectCategoryCreateRequest $request): RedirectResponse
    {
        $response = $this->service->storeOrUpdateProjectCategory($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'projectCategory.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->service->projectCategoryEditData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $item = $response['data'];

        $data['pageTitle'] = __('Update Project Category');
        $data['function_type'] = 'update';
        $data['item'] = $item;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('projectCategory', 'create'));
    }

    public function update(ProjectCategoryCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->service->storeOrUpdateProjectCategory($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'projectCategory.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->service->deleteProjectCategory($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'projectCategory.list');
    }

    public function projectCategoryStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->service->publishProjectCategory($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('projectCategoryStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
