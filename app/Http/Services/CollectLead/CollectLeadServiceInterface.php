<?php

namespace App\Http\Services\CollectLead;

use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface CollectLeadServiceInterface extends BaseServiceInterface
{
    public function submitCustomerInformation(array $data): array;
    public function submitCompanyDetails(array $data): array;
    public function getLeadList(Request $request): array;
    public function getLeadDetail(int $id): array;
    public function updateStatus($id, $status): array;
}
