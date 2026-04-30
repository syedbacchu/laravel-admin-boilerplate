<?php

namespace App\Http\Controllers\Api\Team;

use App\Http\Controllers\Controller;
use App\Http\Resources\Team\TeamListResource;
use App\Http\Resources\Team\TeamDetailsResource;
use App\Http\Services\Team\TeamServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    protected TeamServiceInterface $team;

    public function __construct(TeamServiceInterface $team)
    {
        $this->team = $team;
    }

    /**
     * Team List API
     */
    public function index(Request $request): JsonResponse
    {
        $response = $this->team->getPublicList($request);

        if (!empty($response['data']['data'])) {
            $response['data']['data'] = TeamListResource::collection(
                $response['data']['data']
            );
        }

        return ResponseService::send($response);
    }

    /**
     * Team Details API
     */
    public function show(string $identifier): JsonResponse
    {
        $response = $this->team->getPublicDetails($identifier);

        if (!empty($response['data'])) {
            $response['data'] = new TeamDetailsResource($response['data']);
        }

        return ResponseService::send($response);
    }
}