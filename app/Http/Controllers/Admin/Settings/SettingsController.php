<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Services\Response\ResponseService;
use App\Models\AdminSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Settings');
        $data['groups'] = AdminSettings::orderBy('id')
            ->get()
            ->groupBy('group');
        $data['activeTab'] = request('group') ?? $data['groups']->keys()->first();
        return ResponseService::send([
            'data' => $data,
        ], view: viewss('settings','index'));
    }

    public function update(Request $request, string $group) {
        return redirect()->route('settings.generalSetting',['group' => $group])->with('success', __('Settings updated successfully'));
    }

}





