<?php

namespace App\Http\Controllers\Api\Feature;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureDetailsResource;
use App\Http\Resources\FeatureListResource;
use App\Http\Services\Feature\FeatureServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    protected FeatureServiceInterface $service;

    public function __construct(FeatureServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->service->getPublicFeatureList($request);
        if (
            isset($response['data']['data']) &&
            is_iterable($response['data']['data'])
        ) {
            $response['data']['data'] = FeatureListResource::collection(
                $response['data']['data']
            );
        }
        return ResponseService::send($response);
    }

    public function show(string $identifier): JsonResponse
    {
        $response = $this->service->getPublicFeatureDetails($identifier);

        if (($response['success'] ?? false) === true && isset($response['data'])) {
            $response['data'] = FeatureDetailsResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
