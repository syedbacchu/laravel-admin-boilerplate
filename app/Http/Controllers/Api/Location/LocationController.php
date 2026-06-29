<?php

namespace App\Http\Controllers\Api\Location;

use App\Enums\StatusEnum;
use App\Http\Services\Home\HomeService;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private $service;
    function __construct()
    {
        $this->service = new HomeService();
    }
    public function districts(Request $request): JsonResponse
    {
        $request->merge(['status' => enum(StatusEnum::ACTIVE),
        'list_size' => 'all',]);
        $response = $this->service->getDistrictList($request);
        return ResponseService::send($response);
    }

    public function thanas(Request $request): JsonResponse
    {
        $request->merge(['status' => enum(StatusEnum::ACTIVE),
        'list_size' => 'all',]);
        $response = $this->service->getThanaList($request);
        return ResponseService::send($response);
    }
}
