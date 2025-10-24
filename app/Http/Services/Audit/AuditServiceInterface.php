<?php

namespace App\Http\Services\Audit;

use App\Http\Services\BaseServiceInterface;

interface AuditServiceInterface extends BaseServiceInterface
{

    public function getDataTableData($type=null): mixed; // For DataTable - Service provides this
    public function deleteData($id): mixed; // For delete data
    public function detailsData($id): mixed; // For delete data
}
