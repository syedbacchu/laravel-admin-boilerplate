<?php

namespace App\Http\Services\BatteryWater;

use App\Enums\StatusEnum;
use App\Http\Services\BaseService;
use App\Http\Services\BatteryWater\BatteryWaterLeadServiceInterface;
use App\Http\Services\BatteryWater\BatteryWaterLeadRepositoryInterface;
use App\Mail\LeadSubmissionMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Exception;

class BatteryWaterLeadService extends BaseService implements BatteryWaterLeadServiceInterface
{
    protected BatteryWaterLeadRepositoryInterface $batteryWaterLeadRepository;

    public function __construct(BatteryWaterLeadRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->batteryWaterLeadRepository = $repository;
    }

    public function submitOrderInformation(array $data): array
    {
        $leadData = [
            'name'         => $data['name'],
            'phone'        => $data['phone'],
            'email'        => $data['email'] ?? null,
            'district'     => $data['district'] ?? null,
            'thana'        => $data['thana'] ?? null,
            'address'      => $data['address'] ?? null,
            'bottle_size'  => $data['bottle_size'],
            'quantity'     => $data['quantity'] ?? 1,
            'unit_price'   => $data['unit_price'] ?? 0.00,  
            'total_price'  => $data['total_price'] ?? 0.00, 
            'note'         => $data['note'] ?? null,
            'status'       => $data['status'] ?? StatusEnum::PENDING->value,
        ];

        $lead = $this->batteryWaterLeadRepository->createCustomerLead($leadData);

        try {
            Mail::to('sazzad.reza@bio-xin.com')
                ->send(new LeadSubmissionMail($lead->toArray(), 'customer'));
        } catch (Exception $e) {
            logStore('Lead email sending failed', $e->getMessage());
        }

        return $this->sendResponse(
            true,
            'Order submitted successfully',
            $lead->toArray(),
            201
        );
    }

    public function getLeadList(Request $request): array
    {
        $data = $this->batteryWaterLeadRepository->leadList($request);
        return $this->sendResponse(true, 'Leads retrieved successfully', $data);
    }

    public function getLeadDetail(int $id): array
    {
        $lead = $this->batteryWaterLeadRepository->getLeadById($id);

        if (!$lead) {
            return $this->sendResponse(
                false,
                'Lead not found',
                [],
                404
            );
        }

        return $this->sendResponse(
            true,
            'Lead retrieved successfully',
            $lead,
            200
        );
    }

    public function updateStatus($id, $status): array
    {
        $item = $this->batteryWaterLeadRepository->find($id);

        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->batteryWaterLeadRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }
}