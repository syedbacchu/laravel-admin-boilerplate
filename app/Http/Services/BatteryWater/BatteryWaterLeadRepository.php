<?php

namespace App\Http\Services\BatteryWater;

use App\Http\Repositories\BaseRepository;
use App\Models\BatteryWaterLead;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BatteryWaterLeadRepository extends BaseRepository implements BatteryWaterLeadRepositoryInterface
{
    public function __construct(BatteryWaterLead $model)
    {
        parent::__construct($model);
    }

    public function createCustomerLead(array $data): Model
    {
        return $this->model->create($data);
    }

    public function getLeadsByType(int $type): Collection
    {
        return $this->model->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getLeadById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function leadList(Request $request): array
    {
        return DataListManager::list(
            request: $request,
            query: BatteryWaterLead::query(),
            searchable: [
                'name',
                'phone',
                'email',
                'district',
                'thana',
                'address',
                'bottle_size',
            ],
            select: [
                'id',
                'name',
                'phone',
                'email',
                'district',
                'thana',
                'address',
                'bottle_size',
                'quantity',
                'unit_price',
                'total_price',
                'status',
                'note',
                'admin_note',
                'created_at',
                'updated_at',
            ],
        );
    }
}
