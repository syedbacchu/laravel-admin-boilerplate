<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\CollectLead;
use Illuminate\Database\Eloquent\Model;

class CollectLeadRepository extends BaseRepository implements CollectLeadRepositoryInterface
{
    public function __construct(CollectLead $model)
    {
        parent::__construct($model);
    }

    public function createCustomerLead(array $data): Model
    {
        return $this->model->create($data);
    }

    public function createCompanyLead(array $data): Model
    {
        return $this->model->create($data);
    }
}
