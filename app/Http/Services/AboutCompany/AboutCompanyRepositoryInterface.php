<?php

namespace App\Http\Services\AboutCompany;

use App\Http\Repositories\BaseRepositoryInterface;

interface AboutCompanyRepositoryInterface extends BaseRepositoryInterface
{
    public function getFirst(): ?\App\Models\AboutCompany;
    public function getBySiteType(int $siteType): ?\App\Models\AboutCompany;
}
