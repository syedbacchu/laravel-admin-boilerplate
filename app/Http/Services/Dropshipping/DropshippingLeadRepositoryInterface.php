<?php

namespace App\Http\Services\Dropshipping;

use App\Http\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface DropshippingLeadRepositoryInterface extends BaseRepositoryInterface
{
    public function createDropshippingLead(array $data): Model;
    public function getLeadById(int $id): ?Model;
    public function leadList(Request $request): array;
}
