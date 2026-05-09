<?php

namespace App\Http\Services\ProductFeature;

use App\Http\Requests\Products\ProductFeatureCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface ProductFeatureServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdate(ProductFeatureCreateRequest $request): array;
    public function deleteData($id): array;
    public function publish($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function editData($id): array;
    public function createData(Request $request): array;
}
