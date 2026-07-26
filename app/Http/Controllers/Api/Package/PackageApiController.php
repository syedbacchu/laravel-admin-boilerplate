<?php

namespace App\Http\Controllers\Api\Package;

use App\Http\Controllers\Controller;
use App\Http\Resources\Package\PackageDetailsResource;
use App\Http\Resources\Package\PackageListResource;
use App\Http\Services\Package\PackageServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PackageApiController extends Controller
{
    protected PackageServiceInterface $package;

    public function __construct(PackageServiceInterface $package)
    {
        $this->package = $package;
    }

    /**
     * Package List API
     */
    public function index(Request $request): JsonResponse
    {
        $response = $this->package->getPublicPackageList($request);

        if (!empty($response['data']['data'])) {
            $response['data']['data'] = PackageListResource::collection(
                $response['data']['data']
            );
        }

        return ResponseService::send($response);
    }

    /**
     * Package Details API
     */
    public function show(string $identifier): JsonResponse
    {
        $response = $this->package->getPublicPackageDetails($identifier);

        if (!empty($response['data'])) {
            $response['data'] = new PackageDetailsResource($response['data']);
        }

        return ResponseService::send($response);
    }
}