<?php

namespace App\Http\Controllers\Api\CollectLead;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectLead\CustomerInformationRequest;
use App\Http\Requests\CollectLead\CompanyDetailsRequest;
use App\Http\Services\CollectLead\CollectLeadServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;

class CollectLeadController extends Controller
{
    protected CollectLeadServiceInterface $service;

    public function __construct(CollectLeadServiceInterface $service)
    {
        $this->service = $service;
    }

    public function submitCustomerInformation(CustomerInformationRequest $request): JsonResponse
    {
        $response = $this->service->submitCustomerInformation($request->validated());
        return ResponseService::send($response);
    }

    public function submitCompanyDetails(CompanyDetailsRequest $request): JsonResponse
    {
        $response = $this->service->submitCompanyDetails($request->validated());
        return ResponseService::send($response);
    }
}
