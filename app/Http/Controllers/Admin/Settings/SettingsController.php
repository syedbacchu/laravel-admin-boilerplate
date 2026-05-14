<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Services\Response\ResponseService;
use App\Http\Services\Mail\MailManager;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{

    public function index(Request $request)
    {
        $data = Settings::createData($request)['data'];
        $data['pageTitle'] = __('Settings');
        return ResponseService::send([
            'data' => $data,
        ], view: viewss('settings','index'));
    }

    public function update(Request $request, string $group)
    {
        $response = Settings::updateData($request, $group);
        return ResponseService::send([
            'response' => $response,
        ],'settings.generalSetting', null,
            ['group' => $group],'settings.generalSetting');

    }

    public function testMail(Request $request): JsonResponse
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            $mailManager = new MailManager();

            // Check if mail is configured
            if (!$mailManager->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mail not configured. Please configure SMTP settings first.',
                    'errors' => [
                        'Mail server settings (host, port, username) are required.'
                    ]
                ], 400);
            }

            $mailService = $mailManager->make();
            $result = $mailService->sendTest(
                'emails.test-mail',
                [
                    'test_message' => 'This is a test email from your admin panel to verify your mail configuration is working correctly.',
                'site_name' => settings('app_title', env('APP_NAME', 'Laravel')),
                    'test_time' => now()->format('Y-m-d H:i:s')
                ],
                $request->test_email,
                'Admin User',
                'Test Email from Admin Panel'
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test email sent successfully to ' . $request->test_email . '. Please check your inbox (and spam folder).'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test email',
                    'errors' => [
                        $result['message'] ?? 'Unknown error occurred'
                    ]
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email',
                'errors' => [
                    'Error: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

}





