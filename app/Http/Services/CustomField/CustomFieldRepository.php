<?php

namespace App\Http\Services\CustomField;

use App\Http\Repositories\BaseRepository;
use App\Models\CustomField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CustomFieldRepository extends BaseRepository implements CustomFieldRepositoryInterface
{
    public function __construct(CustomField $model)
    {
        parent::__construct($model);
    }
    public function findData(int $id): Model {
        return $this->find($id);
    }

    public function getByModuleData(string $module): Collection {
        return $this->model
            ->where('module', $module)
            ->orderBy('sort_order')
            ->get();
    }

}
