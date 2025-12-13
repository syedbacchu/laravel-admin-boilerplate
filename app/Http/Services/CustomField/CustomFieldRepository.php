<?php

namespace App\Http\Services\CustomField;

use App\Http\Repositories\BaseRepository;
use App\Models\CustomField;
use Illuminate\Database\Eloquent\Model;

class CustomFieldRepository extends BaseRepository implements CustomFieldRepositoryInterface
{
    public function __construct(CustomField $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): Model
    {
        return $this->create($data);
    }
    public function findData(int $id): Model {
        return $this->find($id);
    }

}
