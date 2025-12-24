<?php

namespace App\Http\Services\User;

use App\Http\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function dataListJoin($request): array;
    public function dataList(Request $request): array;
    public function createData(array $data): Model;
    public function getPermission($id): Model;

    public function updatePermission($id,array $data): mixed;
    public function deletePermission($id): mixed;
    public function permissionList(Request $request): array;
    public function getModulePermissions($guard = null): Collection;
}
