<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Services\Response\ResponseService;
use App\Support\Settings;
use Illuminate\Http\Request;

class CommonSettingController extends Controller
{
    protected array $allowedGroups = [
        'general',
        'contact',
        'social',
        'seo',
        'company',
    ];

    public function index(Request $request)
    {
        $group = (string) $request->get('group', '');

        if (empty($group)) {
            return ResponseService::send([
                'success' => true,
                'message' => __('All common settings retrieved successfully'),
                'data' => Settings::all(),
                'status' => 200,
                'error_message' => '',
            ]);
        }

        if (!in_array($group, $this->allowedGroups, true)) {
            return ResponseService::send([
                'success' => false,
                'message' => __('Invalid settings group'),
                'data' => [],
                'status' => 422,
                'error_message' => __('Invalid settings group'),
            ]);
        }

        return ResponseService::send([
            'success' => true,
            'message' => __('Common settings retrieved successfully'),
            'data' => Settings::group($group),
            'status' => 200,
            'error_message' => '',
        ]);
    }

    public function show(Request $request)
    {
        $key = (string) $request->get('key', '');

        if (empty($key)) {
            return ResponseService::send([
                'success' => false,
                'message' => __('Setting key is required'),
                'data' => [],
                'status' => 422,
                'error_message' => __('Setting key is required'),
            ]);
        }

        $value = Settings::get($key);

        if ($value === null) {
            return ResponseService::send([
                'success' => false,
                'message' => __('Setting not found'),
                'data' => [],
                'status' => 404,
                'error_message' => __('Setting not found'),
            ]);
        }

        return ResponseService::send([
            'success' => true,
            'message' => __('Common setting retrieved successfully'),
            'data' => [
                'key' => $key,
                'value' => $value,
            ],
            'status' => 200,
            'error_message' => '',
        ]);
    }
}
