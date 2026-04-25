<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Services\Project\ProjectServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    protected ProjectServiceInterface $service;

    public function __construct(ProjectServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Project List');
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
                    'category' => fn ($item) => $item->category?->name ?: '-',
                    'thumbnail' => fn ($item) => $item->thumbnail
                        ? '<img src="' . $item->thumbnail . '" alt="' . $item->title . '" class="h-12 w-12 object-cover rounded">'
                        : '-',
                    'project_status' => fn ($item) => $this->getProjectStatusBadge($item->project_status),
                    'start_date' => fn ($item) => $item->start_date ? $item->start_date->format('M d, Y') : '-',
                    'end_date' => fn ($item) => $item->end_date ? $item->end_date->format('M d, Y') : '-',

                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('project.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('project.edit', $item->id)),
                            delete_column(route('project.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['thumbnail', 'project_status', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('project', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->service->projectCreateData($request)['data'];

        $data['pageTitle'] = __('Create Project');
        $data['function_type'] = 'create';
        $data['categories'] = $setup['categories'];

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('project', 'create'));
    }

    public function store(ProjectCreateRequest $request): RedirectResponse
    {
        $response = $this->service->storeOrUpdateProject($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'project.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->service->projectEditData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $setup = $this->service->projectCreateData(request())['data'];
        $item = $response['data'];

        $data['pageTitle'] = __('Update Project');
        $data['function_type'] = 'update';
        $data['item'] = $item;
        $data['categories'] = $setup['categories'];
        $data['selectedCategoryId'] = $item->category_id;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('project', 'create'));
    }

    public function update(ProjectCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->service->storeOrUpdateProject($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'project.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->service->deleteProject($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'project.list');
    }

    public function projectStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->service->publishProject($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('projectStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }

    private function getProjectStatusBadge($status): string
    {
        $badges = [
            'ongoing' => '<span class="badge badge-info">Ongoing</span>',
            'hold' => '<span class="badge badge-warning">On Hold</span>',
            'completed' => '<span class="badge badge-success">Completed</span>',
        ];

        return $badges[$status] ?? '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
    }
}
