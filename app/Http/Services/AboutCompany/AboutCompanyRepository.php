<?php

namespace App\Http\Services\AboutCompany;

use App\Http\Repositories\BaseRepository;
use App\Models\AboutCompany;

class AboutCompanyRepository extends BaseRepository implements AboutCompanyRepositoryInterface
{
    public function __construct(AboutCompany $model)
    {
        parent::__construct($model);
    }

    public function getFirst(): ?AboutCompany
    {
        return AboutCompany::first();
    }

    public function getBySiteType(int $siteType): ?AboutCompany
    {
        return AboutCompany::query()
            ->where('site_type', $siteType)
            ->first();
    }
}
