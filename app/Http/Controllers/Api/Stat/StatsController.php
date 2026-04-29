<?php

namespace App\Http\Controllers\Api\Stat;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatListResource;
use App\Http\Resources\StatDetailsResource;
use App\Http\Services\Stat\StatServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    protected StatServiceInterface $stat;

    public function __construct(StatServiceInterface $stat)
    {
        $this->stat = $stat;
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->stat->getPublicList($request);

        if (!empty($response['data']['data'])) {
            $response['data']['data'] = StatListResource::collection(
                $response['data']['data']
            );
        }

        return ResponseService::send($response);
    }

    public function show(string $id): JsonResponse
    {
        $response = $this->stat->getPublicDetails($id);

        if (!empty($response['data'])) {
            $response['data'] = new StatDetailsResource($response['data']);
        }

        return ResponseService::send($response);
    }
}
