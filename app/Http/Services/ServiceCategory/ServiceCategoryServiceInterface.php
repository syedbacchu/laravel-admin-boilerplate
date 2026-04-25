<?php

namespace App\Http\Services\ServiceCategory;

use App\Http\Requests\Service\ServiceCategoryCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface ServiceCategoryServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdateServiceCategory(ServiceCategoryCreateRequest $request): array;
    public function deleteServiceCategory($id): array;
    public function publishServiceCategory($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function serviceCategoryEditData($id): array;
    public function serviceCategoryCreateData(Request $request): array;
    public function getPublicServiceCategoryList(Request $request): array;
    public function getPublicServiceCategoryDetails(string $identifier): array;
}
