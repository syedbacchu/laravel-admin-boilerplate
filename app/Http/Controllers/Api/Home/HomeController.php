<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Http\Services\Home\HomeService;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected HomeService $service;

    public function __construct(HomeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $request->merge(['status' => $request->status ?? 1, 'per_page' => $request->per_page ?? 4]);
        $response = $this->service->getHomeData($request);
        return ResponseService::send($response);
    }
}
