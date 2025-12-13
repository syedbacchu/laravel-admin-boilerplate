<?php

namespace App\Http\Services\CustomField;

use App\Http\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

interface CustomFieldRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $data): Model;
    public function findData(int $id): Model;
}
