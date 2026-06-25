<?php

namespace App\Http\Controllers\Api\Comparism;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comparism\ComparismDetailsResource;
use App\Http\Resources\Comparism\ComparismListResource;
use App\Http\Services\Comparism\ComparismServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComparismApiController extends Controller
{
    protected ComparismServiceInterface $comparism;

    public function __construct(ComparismServiceInterface $comparism)
    {
        $this->comparism = $comparism;
    }

    /**
     * Comparism List API
     */
    public function index(Request $request): JsonResponse
    {
        $response = $this->comparism->getPublicList($request);

        if (!empty($response['data']['data'])) {
            $response['data']['data'] = ComparismListResource::collection(
                $response['data']['data']
            );
        }

        return ResponseService::send($response);
    }

    /**
     * Comparism Details API
     */
    public function show(string $identifier): JsonResponse
    {
        $response = $this->comparism->getPublicDetails($identifier);

        if (!empty($response['data'])) {
            $response['data'] = new ComparismDetailsResource($response['data']);
        }

        return ResponseService::send($response);
    }
}