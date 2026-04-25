<?php

namespace App\Http\Services\FeatureCategory;

use App\Http\Repositories\BaseRepositoryInterface;

interface FeatureCategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function createFeatureCategory(array $data): \Illuminate\Database\Eloquent\Model;
    public function featureCategoryList(\Illuminate\Http\Request $request): array;
    public function findPublicFeatureCategoryByIdentifier(string $identifier): ?\App\Models\FeatureCategory;
}
