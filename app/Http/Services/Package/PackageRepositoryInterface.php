<?php

namespace App\Http\Services\Package;

use App\Http\Repositories\BaseRepositoryInterface;

interface PackageRepositoryInterface extends BaseRepositoryInterface
{
    public function createPackage(array $data): \Illuminate\Database\Eloquent\Model;
    public function packageList(\Illuminate\Http\Request $request): array;
    public function findPublicPackageByIdentifier(string $identifier): ?\App\Models\Package;
}