<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductDetailResource;
use App\Http\Services\Products\ProductsServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductsServiceInterface $productService;

    public function __construct(ProductsServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get Product List with comprehensive filtering
     */
    public function index(Request $request): JsonResponse
    {
        $response = $this->productService->getPublicList($request);

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
        $response = $this->productService->getPublicDetails($identifier);

        if (($response['success'] ?? false) === true && isset($response['data'])) {
            $response['data'] = ProductDetailResource::make($response['data']);
        }

        return ResponseService::send($response);
    }
}
