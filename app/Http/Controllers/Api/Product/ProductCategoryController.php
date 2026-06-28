<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductDetailResource;
use App\Http\Services\ProductsCategory\ProductsCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    protected ProductsCategoryServiceInterface $productCategoryService;
    protected $service;

    public function __construct(ProductsCategoryServiceInterface $productCategoryService)
    {
        $this->service = $productCategoryService;
    }

    /**
     * Get Product List with comprehensive filtering
     */
    public function index(Request $request): JsonResponse
    {
        $response = $this->service->getPublicList($request);

        if (
            isset($response['data']['data']) &&
            is_iterable($response['data']['data'])
        ) {
            $response['data']['data'] = ProductListResource::collection(
                $response['data']['data']
            );
        }

        return ResponseService::send($response);
    }

    /**
     * Get Product Details
     */
    public function show(string $identifier): JsonResponse
    {
        $response = $this->service->getPublicDetails($identifier);

        if (($response['success'] ?? false) === true && isset($response['data'])) {
            $response['data'] = ProductDetailResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
