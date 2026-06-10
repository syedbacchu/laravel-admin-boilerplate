<?php

namespace App\Http\Controllers\Api\BatteryWaterLead;

use App\Http\Controllers\Controller;
use App\Http\Requests\BatteryWaterLead\BatteryWaterLeadInformationRequest;
use App\Http\Requests\BatteryWaterLead\BatteryWaterLeadCompanyDetailsRequest;
use App\Http\Services\Response\ResponseService;
use App\Http\Resources\BatteryWaterLeadListResource;
use App\Http\Resources\BatteryWaterLeadDetailResource;
use App\Http\Services\BatteryWater\BatteryWaterLeadServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BatteryWaterLeadsController extends Controller
{
    protected BatteryWaterLeadServiceInterface $service;

    public function __construct(BatteryWaterLeadServiceInterface $service)
    {
        $this->service = $service;
    }

    public function submitCustomerInformation(BatteryWaterLeadInformationRequest $request): JsonResponse
    {
        $response = $this->service->submitCustomerInformation($request->validated());
        return ResponseService::send($response);
    }

    public function submitCompanyDetails(BatteryWaterLeadCompanyDetailsRequest $request): JsonResponse
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
            $response['data']['data'] = BatteryWaterLeadListResource::collection(
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
            $response['data'] = BatteryWaterLeadDetailResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
