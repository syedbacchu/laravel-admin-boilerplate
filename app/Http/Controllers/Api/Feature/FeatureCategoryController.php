<?php

namespace App\Http\Controllers\Api\Feature;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureCategoryListResource;
use App\Http\Services\FeatureCategory\FeatureCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeatureCategoryController extends Controller
{
    protected FeatureCategoryServiceInterface $service;

    public function __construct(FeatureCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->service->getPublicFeatureCategoryList($request);
        if (
            isset($response['data']['data']) &&
            is_iterable($response['data']['data'])
        ) {
            $response['data']['data'] = FeatureCategoryListResource::collection(
                $response['data']['data']
            );
        }
        return ResponseService::send($response);
    }

    public function show(string $identifier): JsonResponse
    {
        $response = $this->service->getPublicFeatureCategoryDetails($identifier);

        if (($response['success'] ?? false) === true && isset($response['data'])) {
            $response['data'] = FeatureCategoryListResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
