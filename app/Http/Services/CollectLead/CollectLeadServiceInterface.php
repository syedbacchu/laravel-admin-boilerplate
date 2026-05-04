<?php

namespace App\Http\Services\CollectLead;

use App\Http\Services\BaseServiceInterface;

interface CollectLeadServiceInterface extends BaseServiceInterface
{
    public function submitCustomerInformation(array $data): array;
    public function submitCompanyDetails(array $data): array;
}
