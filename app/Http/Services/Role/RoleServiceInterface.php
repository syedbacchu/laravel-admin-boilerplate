<?php

namespace App\Http\Services\Role;

use App\Http\Requests\Slider\SliderCreateRequest;
use App\Http\Services\BaseServiceInterface;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

interface RoleServiceInterface extends BaseServiceInterface
{

    public function getDataTableData($request): array;
    public function storeOrUpdateSlider(SliderCreateRequest $request): array;
    public function deleteData($id): array;
    public function permissionDelete($id): array;
    public function publishPermission($id,$status): array;
    public function getSinglePermission($id): array;
    public function getPermissionList($request);

}
