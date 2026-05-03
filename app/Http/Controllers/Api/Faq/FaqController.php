<?php

namespace App\Http\Controllers\Api\Faq;

use App\Http\Controllers\Controller;
use App\Http\Services\Faq\FaqServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Http\Resources\FaqResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
    protected FaqServiceInterface $faqService;

    public function __construct(FaqServiceInterface $faqService)
    {
        $this->faqService = $faqService;
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->faqService->getPublicList($request);

        if (!empty($response['data']['data'])) {
            $response['data']['data'] = FaqResource::collection(
                $response['data']['data']
            );
        }

        return ResponseService::send($response);
    }

    public function show(string $identifier): JsonResponse
    {
        $response = $this->faqService->getPublicFaqDetails($identifier);

        if (!empty($response['data'])) {
            $response['data'] = new FaqResource($response['data']);
        }

        return ResponseService::send($response);
    }
}
