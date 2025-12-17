<?php

namespace App\Http\Services\Role;

use App\Http\Repositories\BaseRepository;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function roleList($request): array
    {
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

        $query = Role::query();

        if ($guard) {
            $query->where('guard', $guard);
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
                    ->orWhere('guard', 'like', '%' . $search . '%');
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
}
