<?php

namespace App\Http\Services\Feature;

use App\Http\Requests\Feature\FeatureCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface FeatureServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdateFeature(FeatureCreateRequest $request): array;
    public function deleteFeature($id): array;
    public function publishFeature($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function featureEditData($id): array;
    public function featureCreateData(Request $request): array;
    public function getPublicFeatureList(Request $request): array;
    public function getPublicFeatureDetails(string $identifier): array;
}
