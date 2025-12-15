<?php

namespace App\Http\Services\CustomField;

use App\Enums\StatusEnum;
use App\Http\Requests\CustomField\CustomFieldCreateRequest;
use App\Http\Requests\Slider\SliderCreateRequest;
use App\Http\Services\BaseService;
use App\Http\Services\Response\ModelScannerService;
use App\Traits\FileUploadTrait;

class CustomFieldService extends BaseService implements CustomFieldServiceInterface
{
    use FileUploadTrait;

    protected CustomFieldRepositoryInterface $customRepository;

    public function __construct(CustomFieldRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->customRepository = $repository; // use this specifically
    }


    public function storeOrUpdateItem(CustomFieldCreateRequest $request): array
    {
        $item = "";
        $data = [
            'module' => $request->module,
            'label' => $request->label,
            'name' => $request->name,
            'type' => $request->type,
            'options' => $request->options
                ? array_map('trim', explode(',', $request->options))
                : null,
            'show_in' => $request->show_in,
            'is_required' => (bool)$request->is_required,
            'default_value' => $request->default_value,
            'validation_rules' => $request->validation_rules,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ];
        $message = "";
        if ($request->edit_id) {
            $existItem = $this->customRepository->find($request->edit_id);
            if ($existItem) {
                $this->customRepository->update($existItem->id,$data);
                $item = $this->customRepository->find($existItem->id);
                $message = __('Custom field updated successfully');
            } else {
                return $this->sendResponse(false,__('Data not found'));
            }
        } else {
            $item = $this->customRepository->create($data);
            $message = __('Custom filed created successfully');
        }

        return $this->sendResponse(true,$message,$item);
    }

    public function deleteItem($id): array
    {
        $item = $this->customRepository->find($id);
        if ($item) {
            $this->delete($item->id);
            return $this->sendResponse(true,__('Slider deleted successfully'));
        } else {
            return $this->sendResponse(false,__('Data not found'));
        }
    }

     public function getByModule($module): array
     {
         logStore('MODULE FROM REQUEST:', $module);
         $module = str_replace('\\\\', '\\', $module);
         logStore('MODULE :', $module);
         $data = $this->customRepository->getByModuleData($module);
         return $this->sendResponse(true,__('Data get successfully'), $data);
     }

     public function getModuleData(): array {
         $data['models'] = ModelScannerService::getModels([
             'CustomField','CustomFieldValue','AdminActivityLog','AdminSettings','AuditLog','FileSystem'
         ]);

         return $this->sendResponse(true,__('Data retrieved successfully'),$data);
     }
}
