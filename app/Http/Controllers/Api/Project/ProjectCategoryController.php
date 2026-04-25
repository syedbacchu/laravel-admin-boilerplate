<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCategoryListResource;
use App\Http\Services\ProjectCategory\ProjectCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectCategoryController extends Controller
{
    protected ProjectCategoryServiceInterface $service;

    public function __construct(ProjectCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->service->getPublicProjectCategoryList($request);
        if (
            isset($response['data']['data']) &&
            is_iterable($response['data']['data'])
        ) {
            $response['data']['data'] = ProjectCategoryListResource::collection(
                $response['data']['data']
            );
        }
        return ResponseService::send($response);
    }

    public function show(string $identifier): JsonResponse
    {
        $response = $this->service->getPublicProjectCategoryDetails($identifier);

        if (($response['success'] ?? false) === true && isset($response['data'])) {
            $response['data'] = ProjectCategoryListResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
