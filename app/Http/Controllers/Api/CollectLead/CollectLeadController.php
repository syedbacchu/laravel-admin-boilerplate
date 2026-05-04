<?php

namespace App\Http\Controllers\Api\CollectLead;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectLead\CustomerInformationRequest;
use App\Http\Requests\CollectLead\CompanyDetailsRequest;
use App\Http\Services\CollectLead\CollectLeadServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Http\Resources\CollectLeadListResource;
use App\Http\Resources\CollectLeadDetailResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function index(Request $request): JsonResponse
    {
        $response = $this->service->getLeadList($request);
        if (
            isset($response['data']['data']) &&
            is_iterable($response['data']['data'])
        ) {
            $response['data']['data'] = CollectLeadListResource::collection(
                $response['data']['data']
            );
        }
        return ResponseService::send($response);
    }

    public function show(Request $request): JsonResponse
    {
        $id = (int) $request->query('id');

        if (!$id) {
            return ResponseService::send([
                'success' => false,
                'message' => 'Lead ID is required',
                'data' => [],
                'status' => 400,
            ]);
        }

        $response = $this->service->getLeadDetail($id);

        if (($response['success'] ?? false) === true && isset($response['data'])) {
            $response['data'] = CollectLeadDetailResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
