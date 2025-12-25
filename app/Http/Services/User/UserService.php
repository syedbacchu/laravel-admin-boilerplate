<?php

namespace App\Http\Services\User;

use App\Enums\StatusEnum;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Services\BaseService;
use App\Http\Services\Response\DataService;
use App\Http\Services\Role\RoleServiceInterface;
use App\Support\Helpers;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class UserService extends BaseService implements UserServiceInterface
{
    use FileUploadTrait;

    protected UserRepositoryInterface $itemRepository;

    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->itemRepository = $repository; // use this specifically
    }

    public function getListData($request): array
    {
        $data = $this->itemRepository->dataList($request);
        return $this->sendResponse(true,__('Data get successfully.'),$data);
    }

    public function storeOrUpdateData(UserCreateRequest $request): array
    {
        $item = "";
        $data = DataService::userCreateData($request);
        $message = "";
        if ($request->edit_id) {
            $item = $this->itemRepository->find($request->edit_id);
            if ($item) {
                $this->itemRepository->update($item->id,$data);
                $item = $this->itemRepository->find($item->id);
                $message = __('User updated successfully');
            } else {
                return $this->sendResponse(false,__('Data not found'));
            }
        } else {
            if(isset($request->type) && $request->type == "admin"){
                $data['added_by'] = Auth::user()->id();
            }
            $data['referral_code'] = uniqid().date('');
            $data['username'] = Helpers::generateUniqueUsername($request->name);
            $item = $this->itemRepository->create($data);
            $message = __('User created successfully');
        }

        return $this->sendResponse(true,$message,$item);
    }

    public function deleteData($id): array
    {
        $item = $this->itemRepository->find($id);
        if ($item) {
            $this->delete($item->id);
            return $this->sendResponse(true,__('Data deleted successfully'));
        } else {
            return $this->sendResponse(false,__('Data not found'));
        }
    }

    public function permissionDelete($id): array
    {
        $item = $this->itemRepository->getPermission($id);
        if ($item) {
            $this->itemRepository->deletePermission($item->id);
            return $this->sendResponse(true,__('Data deleted successfully'));
        } else {
            return $this->sendResponse(false,__('Data not found'));
        }
    }

     public function publishPermission($id,$status): array
     {
        $item = $this->itemRepository->getPermission($id);
        if ($item) {
            $this->itemRepository->updatePermission($item->id,['status' => $status]);
            return $this->sendResponse(true,__('Status updated successfully'));
        } else {
            return $this->sendResponse(false,__('Data not found'));
        }
     }

    public function getPermissionList($request) {
        $responseData = $this->itemRepository->permissionList($request);
        return $this->sendResponse(true,__('Data get successfully'),$responseData );
    }
    public function getSinglePermission($id): array {
        return $this->sendResponse(true,__('Data get successfully'),$this->itemRepository->getPermission($id) );
    }

    public function statusUpdate($id,$status): array
    {
        $item = $this->itemRepository->find($id);
        if ($item) {
            $this->itemRepository->update($item->id,['status' => $status]);
            return $this->sendResponse(true,__('Status updated successfully'));
        } else {
            return $this->sendResponse(false,__('Data not found'));
        }
    }

    public function createData($request): array
    {
        $roleService = app(RoleServiceInterface::class);
        $request->merge(['status' => enum(StatusEnum::ACTIVE)]);
        $data['roles'] = $roleService->getDataTableData($request)['data']['data'];
        if($request->id) {
            $data['item'] = $this->itemRepository->find($request->id);
        }

        return $this->sendResponse(true,__('Data get successfully'),$data);
    }

    public function roleEditData($id): array {
        $data['item'] = $this->itemRepository->find($id);
        if ($data['item']) {
            $data['permissions'] = $this->itemRepository->getModulePermissions($data['item']->guard);
            return $this->sendResponse(true,__('Data get successfully'), $data);
        } else {
            return $this->sendResponse(false,__('Data not found'));
        }
    }
}
