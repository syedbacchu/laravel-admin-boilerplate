<?php

namespace App\Http\Services\BatteryWater;

use App\Http\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface BatteryWaterLeadRepositoryInterface extends BaseRepositoryInterface
{
    public function createCustomerLead(array $data): Model;
    public function createCompanyLead(array $data): Model;
    public function getLeadsByType(int $type): Collection;
    public function getLeadById(int $id): ?Model;
    public function leadList(Request $request): array;
}
