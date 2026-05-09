<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Services\Response\ResponseService;
use App\Support\Settings;
use Illuminate\Http\Request;

class CompanyPolicyController extends Controller
{
    protected array $allowedTypes = [
        'privacy_policy',
        'terms_condition',
        'cookie_policy',
    ];

    public function show(Request $request)
    {
        $type = (string) $request->get('type', '');

        if (!in_array($type, $this->allowedTypes, true)) {
            return ResponseService::send([
                'success' => false,
                'message' => __('Invalid policy type'),
                'data' => [],
                'status' => 422,
                'error_message' => __('Invalid policy type'),
            ]);
        }

        return ResponseService::send([
            'success' => true,
            'message' => __('Company policy retrieved successfully'),
            'data' => [
                'type' => $type,
                'content' => Settings::get($type, ''),
            ],
            'status' => 200,
            'error_message' => '',
        ]);
    }
}
