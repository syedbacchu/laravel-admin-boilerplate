<?php

namespace App\Http\Services\Dropshipping;

use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface DropshippingLeadServiceInterface extends BaseServiceInterface
{
    public function submitOrderInformation(array $data): array;
    public function getLeadList(Request $request): array;
    public function getLeadDetail(int $id): array;
    public function updateStatus($id, $status): array;
}
