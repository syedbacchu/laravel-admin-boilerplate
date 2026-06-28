<?php

namespace App\Http\Controllers\Admin\Dropshipping;

use App\Http\Controllers\Controller;
use App\Http\Services\Dropshipping\DropshippingLeadServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DropshippingLeadController extends Controller
{
    protected DropshippingLeadServiceInterface $lead;

    public function __construct(DropshippingLeadServiceInterface $lead)
    {
        $this->lead = $lead;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Dropshipping Lead List');

        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    $response = $this->lead->getLeadList($request);
                    return $response['data']['data'] ?? [];
                },
                columns: [
                    'id' => fn($item) => $item->id,
                    'name' => fn($item) => $item->name ?? '-',
                    'phone' => fn($item) => $item->phone ?? '-',
                    'email' => fn($item) => $item->email ?? '-',
                    'district' => fn($item) => $item->district ?? '-',
                    'thana' => fn($item) => $item->thana ?? '-',
                    'product' => fn($item) => optional($item->product)->name ?? '-',
                    'product_range' => fn($item) => $item->product_range ?? '-',
                    'status_badge' => fn($item) => toggle_column(
                        route('dropshipping.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'created_at' => fn($item) =>
                        $item->created_at
                            ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i')
                            : '-',

                    'actions' => function ($item) {
                        $buttons = [
                            view_column(route('dropshipping.lead.show', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['status_badge', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('dropshipping', 'list'));
    }

    public function show(string $id)
    {
        $response = $this->lead->getLeadDetail((int)$id);
        $leadInstance = $response['data'] ?? null;

        if (!$leadInstance) {
            abort(404, 'Lead not found');
        }

        return ResponseService::send([
            'lead' => $leadInstance,
            'data' => $leadInstance,
        ], null, \App\Http\Services\Response\Viewed::get('dropshipping', 'show'));
    }

    public function collectLeadStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->lead->updateStatus($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('leadStatus', $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => somethingWrong()
            ]);
        }
    }
}