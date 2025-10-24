<?php

namespace App\Http\Services\Audit;

use App\Http\Repositories\BaseRepositoryInterface;

interface AuditRepositoryInterface extends BaseRepositoryInterface
{
    public function getDataTableQuery($type = null): mixed;
    public function deleteData($id): mixed;
}
