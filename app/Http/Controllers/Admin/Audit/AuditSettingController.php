<?php

namespace App\Http\Controllers\Admin\Audit;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;


class AuditSettingController extends Controller
{
    //
    public function settings(Request $request) {
        $data['pageTitle'] = __('Audit Settings');
        $data['disabled'] = [];

        if (!Storage::exists('audit_settings.json')) {
            Storage::put('audit_settings.json', json_encode($data['disabled'], JSON_PRETTY_PRINT));
        } else {
            $data['disabled'] = json_decode(Storage::get('audit_settings.json'), true) ?? [];
        }
        if ($request->ajax()) {
            return $this->modelList();
        }
        return view('admin.audit.model_setting', $data);
    }

    public function modelList() {
        $models = collect(File::files(app_path('Models')))
            ->map(function ($file) {
                return [
                    'class' => 'App\\Models\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME),
                    'name'  => pathinfo($file->getFilename(), PATHINFO_FILENAME),
                ];
            });

        $disabled = [];
        if (Storage::exists('audit_settings.json')) {
            $disabled = json_decode(Storage::get('audit_settings.json'), true) ?? [];
        }

        return DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('status', function ($model) use ($disabled) {
                $isDisabled = isset($disabled[$model['class']]);
                $checked = $isDisabled ? '' : 'checked';
                return '<input type="checkbox" class="toggle-status" data-model="'.$model['class'].'" '.$checked.'>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }


    public function updateModel(Request $request)
    {
        $model = $request->model;

        $enabled = filter_var($request->enabled, FILTER_VALIDATE_BOOLEAN);

        $disabled = [];
        if (Storage::exists('audit_settings.json')) {
            $disabled = json_decode(Storage::get('audit_settings.json'), true) ?? [];
        }

        if ($enabled) {
            unset($disabled[$model]);
        } else {
            $disabled[$model] = false;
        }

        Storage::put('audit_settings.json', json_encode($disabled, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Audit setting updated successfully',
            'data' => $disabled
        ]);
    }

    public function resetModel()
    {
        if (Storage::exists('audit_settings.json')) {
            Storage::delete('audit_settings.json');
        }

        Storage::put('audit_settings.json', json_encode([], JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', 'Audit settings reset successfully! All models are now enabled.');
    }


    public function index(Request $request): View|JsonResponse
    {
        $data['pageTitle'] = __('Audit Report');
        if ($request->ajax()) {
            return $this->getDataTableAuditLog();
        }

        return view('admin.audit.index', $data);
    }

    protected function getDataTableAuditLog(): JsonResponse
    {
        $query = AuditLog::with('user')->latest();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('user', function ($item) {
                return $item->user ? $item->user->name : 'System';
            })
            ->addColumn('model', function ($item) {
                return class_basename($item->model_type);
            })
            ->addColumn('actions', function ($item) {
                return '<button
                            class="btn btn-sm btn-primary view-details"
                            data-id="'.$item->id.'"
                        >
                            View Details
                        </button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show($id)
    {
        $log = AuditLog::findOrFail($id);

        return response()->json([
            'id' => $log->id,
            'user' => optional($log->user)->name ?? 'System',
            'event' => $log->event,
            'model_type' => class_basename($log->model_type),
            'model_id' => $log->model_id,
            'ip_address' => $log->ip_address,
            'user_agent' => $log->user_agent,
            'created_at' => $log->created_at->format('Y-m-d H:i:s'),
            'old_values' => $log->old_values,
            'new_values' => $log->new_values,
        ]);
    }

}
