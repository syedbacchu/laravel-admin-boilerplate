<?php

namespace App\Http\Services\CustomField;

use App\Http\Requests\CustomField\CustomFieldCreateRequest;
use App\Http\Services\BaseServiceInterface;

interface CustomFieldServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdateItem(CustomFieldCreateRequest $request): array;
    public function deleteItem($id): array; // For delete
    public function getByModule(string $module): array;
    public function getModuleData(): array;
}
