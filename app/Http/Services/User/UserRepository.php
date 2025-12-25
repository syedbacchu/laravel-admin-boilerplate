<?php

namespace App\Http\Services\User;

use App\Http\Repositories\BaseRepository;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\DataListManager;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function dataListJoin($request): array
    {
        return DataListManager::list(
            request: $request,
            query: User::query(),
            searchable: [
                'users.name',
                'users.email',
                'roles.name',
            ],
            filters: [
                'status' => 'users.status',
            ],
            select: [
                'users.id',
                'users.name',
                'users.email',
                'roles.name as role_name',
            ],
            notIn:isset($request->notIn) ? $request->notIn : [],
            joinCallback: function ($query) {
                $query->leftJoin('roles', 'roles.id', '=', 'users.role_id');
            }
        );
    }
    public function dataList($request): array
    {
        return DataListManager::list(
            request: $request,
            query: User::query(),

            searchable: [
                'users.name',
                'users.email',
                'users.phone',
                'users.username',
            ],

            filters: [
                'status' => [
                    'column' => 'users.status'
                ],
                'role_module' => [
                    'column' => 'users.role_module'
                ],
                'created_at' => [
                    'column' => 'users.created_at',
                    'type' => 'date'
                ],
                'created_range' => [
                    'column' => 'users.created_at',
                    'type' => 'daterange'
                ],
            ],

            select: [
                'users.id',
                'users.name',
                'users.phone',
                'users.username',
                'users.image',
                'users.role_module',
                'users.role_id',
                'users.status',
                'users.created_at',
            ],
            notIn:isset($request->notIn) ? $request->notIn : [],
        );
    }

    public function createData(array $data): Model
    {
        return $this->create($data);
    }

    public function permissionList($request):array {
        $totalPages = 0;
        $totalCount = 0;
        $settingPerPage = 20;
        $page = isset($request->page) ? intval($request->page) : 1;
        $perPage = isset($request->per_page) ? intval($request->per_page) : $settingPerPage;
        $orderBy = isset($request->orderBy) ? $request->orderBy : 'desc';
        $column = isset($request->orderColumn) ? $request->orderColumn : 'id';
        $search = $request->get('search', null);
        $status = $request->get('status', null);
        $guard = $request->get('guard', null);
        $module = $request->get('module', null);

        $query = Permission::query();

        if ($guard) {
            $query->where('guard', $guard);
        }
        if ($module) {
            $query->where('module', $module);
        }
        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            if (is_array($search)) {
                $search = $request->input('search.value', null);
            }
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('guard', 'like', '%' . $search . '%')
                    ->orWhere('module', 'like', '%' . $search . '%');
            });
        }


        $countQuery = clone $query;
        $totalCount = $countQuery->count();
        $totalPages = ceil($totalCount / $perPage);

        if (isset($request->list_size) && $request->list_size === 'web') {
            $items = $query->orderBy($column, $orderBy)->paginate($settingPerPage);
        } elseif (isset($request->list_size) && $request->list_size === 'all') {
            $items = $query->orderBy($column, $orderBy)->get();
        } elseif (isset($request->list_size) && $request->list_size === 'datatable') {
            $items =  $query->orderBy($column, $orderBy);
        } else {
            $items = $query->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->orderBy($column, $orderBy)
                ->get();
        }
        $data = [
            'total_count' =>  $totalCount,
            'total_page' => $totalPages,
            'per_page' => $perPage,
            'current_page' => $page,
            'data' => $items,
        ];

        return $data;
    }

    public function getPermission($id): Model {
        return Permission::find($id);
    }

    public function updatePermission($id,array $data): mixed {
        return Permission::where('id',$id)->update($data);
    }

    public function deletePermission($id): mixed {
        return Permission::where('id',$id)->delete();
    }

    public function getModulePermissions($guard = null): Collection {
        $query = Permission::query();

        if (!empty($type)) {
            $query->where('guard', $guard);
        }

        return $query
            ->orderBy('module')
            ->get()
            ->groupBy('module');
    }
}
