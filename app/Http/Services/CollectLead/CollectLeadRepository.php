<?php

namespace App\Http\Services\CollectLead;

use App\Http\Repositories\BaseRepository;
use App\Models\CollectLead;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CollectLeadRepository extends BaseRepository implements CollectLeadRepositoryInterface
{
    public function __construct(CollectLead $model)
    {
        parent::__construct($model);
    }

    public function createCustomerLead(array $data): Model
    {
        return $this->model->create($data);
    }

    public function createCompanyLead(array $data): Model
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
            query: CollectLead::query(),
            searchable: [
                'full_name',
                'phone',
                'email',
                'address',
                'company_name',
                'customer_type',
            ],
            filters: [
                'type' => [
                    'column' => 'type',
                ],
                'site_type' => [
                    'column' => 'site_type',
                ],
                'lead_source' => [
                    'column' => 'lead_source',
                ],
            ],
            select: [
                'id',
                'type',
                'site_type',
                'company_name',
                'full_name',
                'phone',
                'whatsapp',
                'email',
                'nid',
                'customer_type',
                'address',
                'district',
                'google_map',
                'installation_site_type',
                'electricity_source',
                'monthly_bill',
                'meter_type',
                'daytime_usage',
                'system_type',
                'system_size_kw',
                'main_purpose',
                'budget_range',
                'payment_preference',
                'roof_size',
                'roof_type',
                'has_shadow',
                'installation_area',
                'lead_source',
                'decision_maker',
                'decision_time',
                'grid_connection',
                'transformer_capacity',
                'contract_demand',
                'monthly_consumption',
                'machinery_load_details',
                'total_connected_load',
                'motor_load_details',
                'total_motor_load',
                'working_shift',
                'peak_load_time',
                'daytime_load_percentage',
                'demand_factor',
                'diversity_factor',
                'maximum_demand',
                'daily_consumption',
                'solar_target_percent',
                'required_capacity_kw',
                'backup_hours',
                'critical_load',
                'inverter_size',
                'panel_size',
                'panel_quantity',
                'estimated_project_cost',
                'expected_payback_period',
                'customer_signature',
                'declaration_date',
                'status',
                'created_at',
                'updated_at',
            ],
        );
    }
}
