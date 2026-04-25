<?php

namespace App\Http\Services\Service;

use App\Http\Requests\Service\ServiceCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface ServiceServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdateService(ServiceCreateRequest $request): array;
    public function deleteService($id): array;
    public function publishService($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function serviceEditData($id): array;
    public function serviceCreateData(Request $request): array;
    public function getPublicServiceList(Request $request): array;
    public function getPublicServiceDetails(string $identifier): array;
}
