<?php

namespace App\Http\Services\CustomField;

use App\Http\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface CustomFieldRepositoryInterface extends BaseRepositoryInterface
{
    public function findData(int $id): Model;
    public function getByModuleData(string $module): Collection;
}
