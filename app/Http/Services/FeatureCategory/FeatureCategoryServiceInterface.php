<?php

namespace App\Http\Services\FeatureCategory;

use App\Http\Requests\Feature\FeatureCategoryCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface FeatureCategoryServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdateFeatureCategory(FeatureCategoryCreateRequest $request): array;
    public function deleteFeatureCategory($id): array;
    public function publishFeatureCategory($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function featureCategoryEditData($id): array;
    public function featureCategoryCreateData(Request $request): array;
    public function getPublicFeatureCategoryList(Request $request): array;
    public function getPublicFeatureCategoryDetails(string $identifier): array;
}
