<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceDetailsResource;
use App\Http\Resources\ServiceListResource;
use App\Http\Services\Service\ServiceServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected ServiceServiceInterface $service;

    public function __construct(ServiceServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->service->getPublicServiceList($request);
        if (
            isset($response['data']['data']) &&
            is_iterable($response['data']['data'])
        ) {
            $response['data']['data'] = ServiceListResource::collection(
                $response['data']['data']
            );
        }
        return ResponseService::send($response);
    }

    public function show(string $identifier): JsonResponse
    {
        $response = $this->service->getPublicServiceDetails($identifier);

        if (($response['success'] ?? false) === true && isset($response['data'])) {
            $response['data'] = ServiceDetailsResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
