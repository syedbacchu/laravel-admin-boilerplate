<?php

namespace App\Http\Services\Dropshipping;

use App\Http\Repositories\BaseRepository;
use App\Models\Dropshipping;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class DropshippingLeadRepository extends BaseRepository implements DropshippingLeadRepositoryInterface
{
    public function __construct(Dropshipping $model)
    {
        parent::__construct($model);
    }

    public function createDropshippingLead(array $data): Model
    {
        return $this->model->create($data);
    }

    public function getLeadById(int $id): ?Model
    {
        return $this->model->with('product')->find($id);
    }

    public function leadList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: Dropshipping::query()->with('product'),
            searchable: [
                'name',
                'phone',
                'email',
                'district',
                'thana',
                'address',
                'product_range',
                'note',
                'country'
            ],
            select: [
                'id',
                'name',
                'phone',
                'email',
                'district',
                'thana',
                'address',
                'product_id',
                'product_range',
                'status',
                'note',
                'created_at',
                'updated_at',
                'country',
            ],
        );
    }
}
