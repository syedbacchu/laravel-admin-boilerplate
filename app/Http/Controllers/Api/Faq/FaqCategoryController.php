<?php

namespace App\Http\Controllers\Api\Faq;

use App\Http\Controllers\Controller;
use App\Http\Services\FaqCategory\FaqCategoryServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Http\Resources\FaqCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FaqCategoryController extends Controller
{
    protected FaqCategoryServiceInterface $faqCategoryService;

    public function __construct(FaqCategoryServiceInterface $faqCategoryService)
    {
        $this->faqCategoryService = $faqCategoryService;
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->faqCategoryService->getPublicFaqCategoryList($request);

        if (!empty($response['data']['data'])) {
            $response['data']['data'] = FaqCategoryResource::collection(
                $response['data']['data']
            );
        }

        return ResponseService::send($response);
    }

    public function show(string $identifier): JsonResponse
    {
        $response = $this->faqCategoryService->getPublicFaqCategoryDetails($identifier);

        if (!empty($response['data'])) {
            $response['data'] = new FaqCategoryResource($response['data']);
        }

        return ResponseService::send($response);
    }
}
