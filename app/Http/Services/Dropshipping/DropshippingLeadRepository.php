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

    public function getLeadById(int $type): ?Model
    {
        return $this->model->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->get();
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
            ],
        );
    }
}
