<?php

namespace App\Http\Repositories\AboutCompany;

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
}
