<?php

namespace App\Http\Services\AboutCompany;

use App\Http\Requests\AboutCompany\AboutCompanyUpdateRequest;
use App\Http\Services\BaseServiceInterface;

interface AboutCompanyServiceInterface extends BaseServiceInterface
{
    public function getAboutCompanyData(int $siteType = 1): array;
    public function updateAboutCompany(AboutCompanyUpdateRequest $request): array;
}
