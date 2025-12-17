<?php

namespace App\Http\Services\Role;

use App\Enums\StatusEnum;
use App\Enums\UploadFolderEnum;
use App\Http\Requests\Slider\SliderCreateRequest;
use App\Http\Services\BaseService;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Builder;

class RoleService extends BaseService implements RoleServiceInterface
{
    use FileUploadTrait;

    protected RoleRepositoryInterface $itemRepository;

    public function __construct(RoleRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->itemRepository = $repository; // use this specifically
    }

    public function getDataTableData($request): array
    {
        $data = $this->itemRepository->roleList($request);
        return $this->sendResponse(true,__('Data get successfully.'),$data);
    }

    public function storeOrUpdateSlider(SliderCreateRequest $request): array
    {
        $item = "";
        $data = [
            'type' => $request->type,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'offer' => $request->offer,
            'link' => $request->link,
            'serial' => isset($request->serial) ? $request->serial : 0 ,
            'published' => $request->published ? $request->published : StatusEnum::ACTIVE
        ];
        $message = "";
        if ($request->edit_id) {
            $existItem = $this->itemRepository->find($request->edit_id);
            if ($existItem) {
                if ($request->photo) {
                    $data['photo'] = $request->photo;
//                    $data['photo'] = $this->uploadFilePublic($request->file('photo'),UploadFolderEnum::GENERAL->value, $existItem->raw_photo);
                }
                $this->itemRepository->update($existItem->id,$data);
                $item = $this->itemRepository->find($existItem->id);
                $message = __('Slider updated successfully');
            } else {
                return $this->sendResponse(false,__('Data not found'));
            }
        } else {
            if ($request->photo) {
                $data['photo'] = $request->photo;
//                $data['photo'] = $this->uploadFilePublic($request->file('photo'),UploadFolderEnum::GENERAL->value);
            }
            $item = $this->itemRepository->createSlider($data);
            $message = __('Slider created successfully');
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
}
