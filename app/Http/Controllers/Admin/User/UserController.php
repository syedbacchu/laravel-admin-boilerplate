<?php

namespace App\Http\Controllers\Admin\User;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\Response\ResponseService;
use App\Http\Services\User\UserServiceInterface;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected UserServiceInterface $service;

    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['pageTitle'] = __('User List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->service
                        ->getListData($request)['data']['data'];
                },
                columns: [
                    'name' => function ($item) {
                        return '
                        <div class="flex items-center gap-2">
                            <img src="'.$item->image.'" class="w-8 h-8 rounded-full">
                            <p>'.$item->name.'</p>
                        </div>';
                    },

                    'created_at' => fn ($item) =>
                    $item->created_at?->diffForHumans(),
                    'role_module' => fn ($item) =>
                        'Admin',
                    'phone' => fn ($item) =>
                    $item->mobile . '<br>' .$item->username,

                    'status' => fn ($item) =>
                    toggle_column(
                        route('user.status'),
                        $item->id,
                        $item->status == 1
                    ),

                    'actions' => fn ($item) =>
                    action_buttons([
                        edit_column(route('user.edit', $item->id)),
                        delete_column(route('user.delete', $item->id)),
                    ]),
                ],
                rawColumns: ['name','phone', 'status', 'actions']
            );
        }


        return ResponseService::send([
            'data' => $data,
        ], view: viewss('user','list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['pageTitle'] = __('Create New User');
        $request->merge(['guard' => 'web']);
        $data['roles'] = $this->service->createData($request)['data']['data']['data'];

        return ResponseService::send([
            'data' => $data,
        ], view: viewss('user','create'));
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
}
