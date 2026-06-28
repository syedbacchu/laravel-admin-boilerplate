<?php

namespace App\Http\Controllers\Api\Dropshipping;

use App\Http\Controllers\Controller;
use App\Http\Requests\DropshippingLead\DropshippingLeadDetailsRequest;
use App\Http\Requests\DropshippingLead\DropshippingLeadInformationRequest;
use App\Http\Services\Response\ResponseService;
use App\Http\Resources\DropshippingLeadDetailResource;
use App\Http\Resources\DropshippingLeadListResource;
use App\Http\Services\Dropshipping\DropshippingLeadServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DropshippingApiController extends Controller
{
    protected DropshippingLeadServiceInterface $service;

    public function __construct(DropshippingLeadServiceInterface $service)
    {
        $this->service = $service;
    }

    public function submitOrderInformation(DropshippingLeadInformationRequest $request): JsonResponse
    {
        $response = $this->service->submitOrderInformation($request->validated());
        return ResponseService::send($response);
    }

    public function submitDropshippingDetails(DropshippingLeadDetailsRequest $request): JsonResponse
    {
        $response = $this->service->getLeadList($request->validated());
        return ResponseService::send($response);
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->service->getLeadList($request);
        if (
            isset($response['data']['data']) &&
            is_iterable($response['data']['data'])
        ) {
            $response['data']['data'] = DropshippingLeadListResource::collection(
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
            $response['data'] = DropshippingLeadDetailResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
