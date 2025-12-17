<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use App\Http\Services\Response\ResponseService;
use App\Http\Services\Role\RoleServiceInterface;
use App\Models\Permission;
use App\Support\SyncPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected RoleServiceInterface $service;

    public function __construct(RoleServiceInterface $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Web Based Role');
        $data['type'] = 'web';
        if ($request->ajax()) {
            return $this->getDataTableDataSet($request,$data['type']);
        }

        return ResponseService::send([
            'data' => $data,
        ], view: viewss('role','list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['pageTitle'] = __('Create New Role');
        $data['function_type'] = 'create';
        $data['permissions'] = Permission::where('guard', 'web')
        ->orderBy('module')
        ->get()
        ->groupBy('module');
        return ResponseService::send([
            'data' => $data,
        ], view: viewss('role','create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    protected function getDataTableDataSet($request,$guard): JsonResponse
    {
        $request->merge(['guard' => $guard, 'list_size' => 'datatable']);
        $query = $this->service->getDataTableData($request)['data']['data'];

        return DataTables::eloquent($query)

            ->addColumn('status', function ($item) {
                return toggle_column(
                    route('role.permissionStatus'),
                    $item->id,
                    $item->status == 1
                );
            })

            ->rawColumns(['status'])
            ->make(true);
    }

    public function syncPermission(Request $request){
        $response = SyncPermission::sync($request);
        return ResponseService::send([
            'response' => $response,
        ]);
    }

    public function webPermission(Request $request)
    {
        $data['pageTitle'] = __('Web Permission');
        $data['type'] = 'web';
        if ($request->ajax()) {
            return $this->getPermissionTableDataSet($request,$data['type']);
        }

        return ResponseService::send([
            'data' => $data,
        ], view: viewss('role','permission'));
    }

    public function apiPermission(Request $request)
    {
        $data['pageTitle'] = __('Api Permission');
        $data['type'] = 'api';
        if ($request->ajax()) {
            return $this->getPermissionTableDataSet($request,$data['type']);
        }

        return ResponseService::send([
            'data' => $data,
        ], view: viewss('role','permissionApi'));
    }

    protected function getPermissionTableDataSet(Request $request, $guard)
    {
        $request->merge(['guard' => $guard,'list_size' => 'datatable']);
        $query = $this->service->getPermissionList($request)['data']['data'];

        // Safety check
        if (!$query instanceof \Illuminate\Database\Eloquent\Builder) {
            throw new \Exception('Datatable expects Eloquent Builder only');
        }
        return DataTables::eloquent($query)

            ->addColumn('status', function ($item) {
                return toggle_column(
                    route('role.permissionPublish'),
                    $item->id,
                    $item->status == 1
                );
            })

            ->addColumn('actions', function ($item) {
                return action_buttons([
                    delete_column(route('role.deletePermission', $item->id)),
                ]);
            })
            ->rawColumns(['actions','status'])
            ->make(true);
    }

    public function permissionPublish(Request $request): JsonResponse {
        try {
            $response = $this->service->publishPermission($request->id,$request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('permissionPublish',$e->getMessage());
            return response()->json(['success'=>false,'message'=>somethingWrong()]);
        }
    }

    public function deletePermission($id): RedirectResponse {
        $response = $this->service->permissionDelete($id);
        return ResponseService::send([
            'response' => $response,
        ]);
    }

    public function apiRole(Request $request)
    {
        $data['pageTitle'] = __('Api Based Role');
        $data['type'] = 'api';
        if ($request->ajax()) {
            return $this->getDataTableDataSet($request,$data['type']);
        }

        return ResponseService::send([
            'data' => $data,
        ], view: viewss('role','apiList'));
    }
}
