<?php

namespace App\Http\Services\ProductFeature;

use App\Http\Repositories\BaseRepositoryInterface;
use App\Models\ProductFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ProductFeatureRepositoryInterface extends BaseRepositoryInterface
{
    public function createProductFeature(array $data): Model;
    public function productFeatureList(Request $request): array;
    public function findByIdentifier(string $identifier): ?ProductFeature;
}
