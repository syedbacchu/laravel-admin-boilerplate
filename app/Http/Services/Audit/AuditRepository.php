<?php

namespace App\Http\Services\Audit;

use App\Http\Repositories\BaseRepository;
use App\Models\AuditLog;

class AuditRepository extends BaseRepository implements AuditRepositoryInterface
{
    public function __construct(AuditLog $model)
    {
        parent::__construct($model);
    }

    public function getDataTableQuery($type = null): mixed
    {
        if (!empty($type)) {
            return AuditLog::with('user')->where(['model_type' => $type])->latest();
        } else {
            return AuditLog::latest();
        }
    }

    public function deleteData($id): mixed
    {
        return $this->delete($id);
    }

}
