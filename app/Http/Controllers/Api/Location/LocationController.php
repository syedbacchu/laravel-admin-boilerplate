<?php

namespace App\Http\Controllers\Api\Location;

use App\Models\District;
use App\Models\Thana;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function districts(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => District::where('status', 1)
                ->orderBy('name')
                ->get(['id', 'code', 'name']),
        ]);
    }

    public function thanas(string $districtCode): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Thana::where('status', 1)
                ->where('district_code', $districtCode)
                ->orderBy('name')
                ->get(['id', 'code', 'name', 'district_code']),
        ]);
    }
}
