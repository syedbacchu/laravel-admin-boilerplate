<?php

namespace App\Http\Controllers\Admin\BatteryWater;

use App\Http\Controllers\Controller;
use App\Http\Services\BatteryWater\BatteryWaterLeadServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BatteryWaterLeadController extends Controller
{
    protected BatteryWaterLeadServiceInterface $lead;

    public function __construct(BatteryWaterLeadServiceInterface $lead)
    {
        $this->lead = $lead;
    }

    /*
    |--------------------------------------------------------------------------
    | LIST (DataTable)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $data['pageTitle'] = __(' Battery Water Lead List');

        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    $response = $this->lead->getLeadList($request);
                    // Standardizes the object extractor to match data structures
                    return $response['data']['data'] ?? [];
                },
                columns: [
                    'id' => fn($item) => $item->id,

                    'name' => fn($item) => $item->name ?? '-',

                    'phone' => fn($item) => $item->phone ?? '-',

                    'district' => fn($item) => $item->district ?? '-',

                    'bottle_size' => fn($item) => $item->bottle_size ?? '-',

                    'quantity' => fn($item) => $item->quantity ?? 0,

                    'status_badge' => fn($item) => toggle_column(
                        route('battery.water.lead.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'created_at' => fn($item) => $item->created_at
                        ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i')
                        : '-',

                    'actions' => function ($item) {
                        $buttons = [
                            view_column(route('battery.water.lead.show', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['status_badge', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('battery-water', 'list'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(string $id)
    {
        $response = $this->lead->getLeadDetail((int)$id);
        $leadInstance = $response['data'] ?? null;

        if (!$leadInstance) {
            abort(404, 'Lead not found');
        }

        return ResponseService::send([
            'lead'        => $leadInstance,
            'collectLead' => $leadInstance,
            'data'        => $leadInstance,
        ], null, \App\Http\Services\Response\Viewed::get('battery-water', 'show')); 
    }

    public function collectLeadStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->lead->updateStatus($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('leadStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}