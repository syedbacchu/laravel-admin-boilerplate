<?php

namespace App\Http\Repositories\AboutCompany;

use App\Http\Repositories\BaseRepositoryInterface;

interface AboutCompanyRepositoryInterface extends BaseRepositoryInterface
{
    public function getFirst(): ?\App\Models\AboutCompany;
}
