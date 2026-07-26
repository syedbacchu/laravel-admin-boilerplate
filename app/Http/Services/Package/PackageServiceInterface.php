<?php

namespace App\Http\Services\Package;

use App\Http\Requests\Package\PackageCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface PackageServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdatePackage(PackageCreateRequest $request): array;
    public function deletePackage($id): array;
    public function publishPackage($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function packageEditData($id): array;
    public function packageCreateData(Request $request): array;
    public function getPublicPackageList(Request $request): array;
    public function getPublicPackageDetails(string $identifier): array;
}