<?php

namespace App\Http\Services\ProductsCategory;

use App\Http\Requests\ProductsCategroy\ProductsCategoryCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface ProductsCategoryServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdate(ProductsCategoryCreateRequest $request): array;
    public function deleteData($id): array;
    public function publish($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function editData($id): array;
    public function createData(Request $request): array;
    public function getPublicList(Request $request): array;
    public function getHomeCategoryList(Request $request): array;
    public function getPublicDetails(string $identifier): array;
}
