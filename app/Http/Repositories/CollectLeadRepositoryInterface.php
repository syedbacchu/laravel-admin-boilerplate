<?php

namespace App\Http\Repositories;

use Illuminate\Database\Eloquent\Model;

interface CollectLeadRepositoryInterface extends BaseRepositoryInterface
{
    public function createCustomerLead(array $data): Model;
    public function createCompanyLead(array $data): Model;
}
