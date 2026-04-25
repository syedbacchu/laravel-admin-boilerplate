<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCategoryListResource;
use App\Http\Services\ServiceCategory\ServiceCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Models\ServiceCategory;
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
        try {
            $categories = ServiceCategory::query()
                ->where('status', 1)
                ->where('is_featured', 1)
                ->ordered()
                ->get(['id', 'name', 'slug', 'description', 'image', 'sort_order']);

            $data = ServiceCategoryListResource::collection($categories);

            return response()->json([
                'success' => true,
                'message' => __('Service categories retrieved successfully'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            logStore('serviceCategoryList', $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => somethingWrong(),
                'data' => [],
            ], 500);
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $category = ServiceCategory::query()
                ->where('slug', $slug)
                ->where('status', 1)
                ->with(['services' => function ($query) {
                    $query->where('status', 1)->ordered();
                }])
                ->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => __('Service category not found'),
                    'data' => [],
                ], 404);
            }

            $data = ServiceCategoryListResource::make($category);

            return response()->json([
                'success' => true,
                'message' => __('Service category details retrieved successfully'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            logStore('serviceCategoryDetails', $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => somethingWrong(),
                'data' => [],
            ], 500);
        }
    }
}
