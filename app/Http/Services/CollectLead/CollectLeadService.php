<?php

namespace App\Http\Services\CollectLead;

use App\Http\Services\BaseService;
use App\Http\Services\CollectLead\CollectLeadServiceInterface;
use App\Http\Repositories\CollectLeadRepositoryInterface;

class CollectLeadService extends BaseService implements CollectLeadServiceInterface
{
    protected CollectLeadRepositoryInterface $collectLeadRepository;

    public function __construct(CollectLeadRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->collectLeadRepository = $repository;
    }

    public function submitCustomerInformation(array $data): array
    {
        $leadData = [
            'type' => $data['type'] ?? 1,
            'site_type' => $data['site_type'] ?? 1,
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'whatsapp' => $data['whatsapp'] ?? null,
            'email' => $data['email'] ?? null,
            'nid' => $data['nid'] ?? null,
            'customer_type' => $data['customer_type'],
            'address' => $data['address'],
            'district' => $data['district'],
            'google_map' => $data['google_map'] ?? null,
            'installation_site_type' => $data['installation_site_type'],
            'electricity_source' => $data['electricity_source'],
            'monthly_bill' => $data['monthly_bill'] ?? 0,
            'meter_type' => $data['meter_type'],
            'daytime_usage' => $data['daytime_usage'],
            'system_type' => $data['system_type'],
            'system_size_kw' => $data['system_size_kw'] ?? null,
            'main_purpose' => $data['main_purpose'],
            'budget_range' => $data['budget_range'],
            'payment_preference' => $data['payment_preference'],
            'roof_size' => $data['roof_size'],
            'roof_type' => $data['roof_type'],
            'has_shadow' => $data['has_shadow'] ?? false,
            'installation_area' => $data['installation_area'],
            'lead_source' => $data['lead_source'],
            'decision_maker' => $data['decision_maker'],
            'decision_time' => $data['decision_time'],
            'customer_signature' => $data['customer_signature'] ?? null,
            'declaration_date' => $data['declaration_date'] ?? null,
        ];

        $lead = $this->collectLeadRepository->createCustomerLead($leadData);

        return $this->sendResponse(
            true,
            'Customer information submitted successfully',
            $lead,
            201
        );
    }

    public function submitCompanyDetails(array $data): array
    {
        $leadData = [
            'type' => 2,
            'site_type' => $data['site_type'] ?? 1,
            'company_name' => $data['company_name'],
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'google_map' => $data['google_map'] ?? null,
            'grid_connection' => $data['grid_connection'],
            'transformer_capacity' => $data['transformer_capacity'] ?? null,
            'contract_demand' => $data['contract_demand'] ?? null,
            'monthly_bill' => $data['monthly_bill'] ?? 0,
            'monthly_consumption' => $data['monthly_consumption'] ?? null,
            'machinery_load_details' => $data['machinery_load_details'] ?? null,
            'total_connected_load' => $data['total_connected_load'] ?? 0,
            'motor_load_details' => $data['motor_load_details'] ?? null,
            'total_motor_load' => $data['total_motor_load'] ?? 0,
            'working_shift' => $data['working_shift'],
            'peak_load_time' => $data['peak_load_time'],
            'daytime_load_percentage' => $data['daytime_load_percentage'] ?? 0,
            'demand_factor' => $data['demand_factor'] ?? null,
            'diversity_factor' => $data['diversity_factor'] ?? null,
            'maximum_demand' => $data['maximum_demand'] ?? null,
            'daily_consumption' => $data['daily_consumption'] ?? null,
            'solar_target_percent' => $data['solar_target_percent'] ?? null,
            'required_capacity_kw' => $data['required_capacity_kw'] ?? null,
            'system_size_kw' => $data['system_size_kw'] ?? null,
            'roof_size' => $data['roof_size'],
            'roof_type' => $data['roof_type'],
            'has_shadow' => $data['has_shadow'] ?? false,
            'backup_hours' => $data['backup_hours'] ?? null,
            'critical_load' => $data['critical_load'] ?? null,
            'inverter_size' => $data['inverter_size'] ?? null,
            'panel_size' => $data['panel_size'] ?? null,
            'panel_quantity' => $data['panel_quantity'] ?? null,
            'estimated_project_cost' => $data['estimated_project_cost'] ?? 0,
            'expected_payback_period' => $data['expected_payback_period'] ?? 0,
            'customer_signature' => $data['customer_signature'] ?? null,
            'declaration_date' => $data['declaration_date'] ?? null,
        ];

        $lead = $this->collectLeadRepository->createCompanyLead($leadData);

        return $this->sendResponse(
            true,
            'Company details submitted successfully',
            $lead,
            201
        );
    }
}
