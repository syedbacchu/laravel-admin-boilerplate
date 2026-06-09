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

    protected array $webFields = [
        'app_title',
        'tag_title',
        'company_email',
        'company_address',
        'helpline',
        'logo',
        'favicon',
        'copyright_text',
        'lang',
        'currency',
        'footer_about_us'
    ];

    public function index(Request $request)
    {
        $groups = $request->get('group', '');

        if (empty($groups)) {
            return ResponseService::send([
                'success' => true,
                'message' => __('All common settings retrieved successfully'),
                'data' => Settings::all(),
                'status' => 200,
                'error_message' => '',
            ]);
        }

        // Support multiple groups (comma-separated)
        $groupArray = array_map('trim', explode(',', $groups));

        // Validate all groups
//        $invalidGroups = array_diff($groupArray, $this->allowedGroups);
//        if (!empty($invalidGroups)) {
//            return ResponseService::send([
//                'success' => false,
//                'message' => __('Invalid settings group'),
//                'data' => [
//                    'invalid_groups' => array_values($invalidGroups),
//                    'allowed_groups' => $this->allowedGroups,
//                ],
//                'status' => 422,
//                'error_message' => __('Invalid settings group'),
//            ]);
//        }

        // Get settings for requested groups
        $result = [];
        foreach ($groupArray as $group) {
            $result[$group] = Settings::group($group);
        }

        // If single group, return just that group's data
        // If multiple groups, return grouped data
        return ResponseService::send([
            'success' => true,
            'message' => count($groupArray) > 1
                ? __('Common settings retrieved successfully')
                : __('Common settings retrieved successfully'),
            'data' => count($groupArray) === 1 ? $result[$groupArray[0]] : $result,
            'status' => 200,
            'error_message' => '',
        ]);
    }

    public function show(Request $request)
    {
        $keys = $request->get('key', '');

        if (empty($keys)) {
            return ResponseService::send([
                'success' => false,
                'message' => __('Setting key is required'),
                'data' => [],
                'status' => 422,
                'error_message' => __('Setting key is required'),
            ]);
        }

        // Support multiple keys (comma-separated)
        $keyArray = array_map('trim', explode(',', $keys));
        $result = [];
        $allSettings = Settings::all();

        foreach ($keyArray as $key) {
            $result[$key] = $allSettings[$key] ?? '';
        }

        return ResponseService::send([
            'success' => true,
            'message' => count($keyArray) > 1
                ? __('Common settings retrieved successfully')
                : __('Common setting retrieved successfully'),
            'data' => count($keyArray) > 1 ? $result : [
                'key' => $keyArray[0],
                'value' => $result[$keyArray[0]],
            ],
            'status' => 200,
            'error_message' => '',
        ]);
    }

    public function forWeb(Request $request)
    {
        $request->merge(['key' => implode(",",$this->webFields)]);
        $keys = $request->get('key', '');

        if (empty($keys)) {
            return ResponseService::send([
                'success' => false,
                'message' => __('Setting key is required'),
                'data' => [],
                'status' => 422,
                'error_message' => __('Setting key is required'),
            ]);
        }

        // Support multiple keys (comma-separated)
        $keyArray = array_map('trim', explode(',', $keys));
        $result = [];
        $allSettings = Settings::all();

        foreach ($keyArray as $key) {
            $result[$key] = $allSettings[$key] ?? '';
        }

        return ResponseService::send([
            'success' => true,
            'message' => count($keyArray) > 1
                ? __('Common settings retrieved successfully')
                : __('Common setting retrieved successfully'),
            'data' => count($keyArray) > 1 ? $result : [
                'key' => $keyArray[0],
                'value' => $result[$keyArray[0]],
            ],
            'status' => 200,
            'error_message' => '',
        ]);
    }
}
