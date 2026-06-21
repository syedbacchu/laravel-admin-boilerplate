<?php

namespace App\Http\Services\BatteryWater;

use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface BatteryWaterLeadServiceInterface extends BaseServiceInterface
{
    public function submitOrderInformation(array $data): array;
    public function getLeadList(Request $request): array;
    public function getLeadDetail(int $id): array;
    public function updateStatus($id, $status): array;
}
