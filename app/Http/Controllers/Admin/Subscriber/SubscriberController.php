<?php

namespace App\Http\Controllers\Admin\Subscriber;

use App\Http\Controllers\Controller;
use App\Http\Services\Response\ResponseService;
use App\Http\Services\Subscriber\SubscriberServiceInterface;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    protected SubscriberServiceInterface $service;

    public function __construct(SubscriberServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Newsletter Subscribers');

        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->service
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'email' => fn ($item) => $item->email,
                    'status' => fn ($item) => $item->status === 1
                        ? '<span class="badge bg-success/10 text-success">Active</span>'
                        : '<span class="badge bg-danger/10 text-danger">Inactive</span>',
                    'created_at' => fn ($item) => $item->created_at->format('M j, Y'),
                    'actions' => function ($item) {
                        $statusButton = $item->status === 1
                            ? '<button type="button" onclick="toggleStatus(' . $item->id . ')" class="btn btn-sm btn-outline-warning">Deactivate</button>'
                            : '<button type="button" onclick="toggleStatus(' . $item->id . ')" class="btn btn-sm btn-outline-success">Activate</button>';

                        $deleteButton = '<button type="button" onclick="deleteSubscriber(' . $item->id . ')" class="btn btn-sm btn-outline-danger">Delete</button>';

                        return $statusButton . ' ' . $deleteButton;
                    },
                ],
                rawColumns: ['status', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, viewss('subscriber', 'list'));
    }

    public function toggleStatus(Request $request): JsonResponse
    {
        $id = (int) $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriber ID is required',
                'data' => [],
                'status' => 400,
            ], 400);
        }

        $response = $this->service->toggleStatus($id);
        return response()->json($response);
    }

    public function destroy(Request $request): JsonResponse
    {
        $id = (int) $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriber ID is required',
                'data' => [],
                'status' => 400,
            ], 400);
        }

        try {
            $this->service->delete($id);
            return response()->json([
                'success' => true,
                'message' => 'Subscriber deleted successfully',
                'data' => [],
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subscriber',
                'data' => [],
                'status' => 500,
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }
}
