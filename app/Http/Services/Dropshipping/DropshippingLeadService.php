<?php

namespace App\Http\Services\Dropshipping;

use App\Enums\StatusEnum;
use App\Http\Services\BaseService;
use App\Http\Services\Dropshipping\DropshippingLeadRepositoryInterface;
use App\Mail\LeadSubmissionMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Exception;

class DropshippingLeadService extends BaseService implements DropshippingLeadServiceInterface
{
    protected DropshippingLeadRepositoryInterface $dropshippingLeadRepository;

    public function __construct(DropshippingLeadRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->dropshippingLeadRepository = $repository;
    }

    public function submitOrderInformation(array $data): array
    {
        $leadData = [
            'name'          => $data['name'],
            'phone'         => $data['phone'],
            'email'         => $data['email'] ?? null,

            'district'      => $data['district'] ?? null,
            'thana'         => $data['thana'] ?? null,
            'address'       => $data['address'] ?? null,
            'country'       => $data['country'] ?? null,

            'product_id'    => $data['product_id'] ?? null,
            'product_range' => $data['product_range'] ?? null,

            'note'          => $data['note'] ?? null,

            'status'        => $data['status'] ?? StatusEnum::PENDING->value,
        ];

        $lead = $this->dropshippingLeadRepository->createDropshippingLead($leadData);

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
        $data = $this->dropshippingLeadRepository->leadList($request);
        return $this->sendResponse(true, 'Leads retrieved successfully', $data);
    }

    public function getLeadDetail(int $id): array
    {
        $lead = $this->dropshippingLeadRepository->getLeadById($id);

        if (!$lead) {
            return $this->sendResponse(false, 'Lead not found', [], 404);
        }

        return $this->sendResponse(true, 'Lead retrieved successfully', $lead);
    }

    public function updateStatus($id, $status): array
    {
        $item = $this->dropshippingLeadRepository->find($id);

        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->dropshippingLeadRepository->update($id, [
            'status' => (int) $status
        ]);

        return $this->sendResponse(true, __('Status updated successfully'));
    }
}