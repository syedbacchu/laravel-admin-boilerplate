<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCategoryListResource;
use App\Http\Services\ServiceCategory\ServiceCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    protected ServiceCategoryServiceInterface $service;

    public function __construct(ServiceCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->service->getPublicServiceCategoryList($request);
        if (
            isset($response['data']['data']) &&
            is_iterable($response['data']['data'])
        ) {
            $response['data']['data'] = ServiceCategoryListResource::collection(
                $response['data']['data']
            );
        }
        return ResponseService::send($response);
    }

    public function show(string $identifier): JsonResponse
    {
        $response = $this->service->getPublicServiceCategoryDetails($identifier);

        if (($response['success'] ?? false) === true && isset($response['data'])) {
            $response['data'] = ServiceCategoryListResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
