<?php

namespace App\Http\Services\Feature;

use App\Http\Repositories\BaseRepositoryInterface;

interface FeatureRepositoryInterface extends BaseRepositoryInterface
{
    public function createFeature(array $data): \Illuminate\Database\Eloquent\Model;
    public function featureList(\Illuminate\Http\Request $request): array;
    public function findPublicFeatureByIdentifier(string $identifier): ?\App\Models\Feature;
}
