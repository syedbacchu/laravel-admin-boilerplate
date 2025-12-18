<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Services\Response\ResponseService;
use App\Models\AdminSettings;
use App\Models\SettingsField;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
//    public function index(Request $request)
//    {
//        $data['pageTitle'] = __('Settings');
//        $data['groups'] = AdminSettings::orderBy('id')
//            ->get()
//            ->groupBy('group');
//        $data['activeTab'] = request('group') ?? $data['groups']->keys()->first();
//        return ResponseService::send([
//            'data' => $data,
//        ], view: viewss('settings','index'));
//    }
//
//    public function update(Request $request, string $group) {
//        return redirect()->route('settings.generalSetting',['group' => $group])->with('success', __('Settings updated successfully'));
//    }
    public function index(Request $request)
    {
        $groups = SettingsField::where('status', 1)
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group');

        $activeTab = $request->get('group') ?? $groups->keys()->first();

        // preload existing values
        $values = AdminSettings::pluck('value', 'slug');

        return view(viewss('settings','index'), [
            'pageTitle' => __('Settings'),
            'groups'    => $groups,
            'values'    => $values,
            'activeTab' => $activeTab,
        ]);
    }

    public function update(Request $request, string $group)
    {
        $fields = SettingsField::where('group', $group)
            ->where('status', 1)
            ->get();

        /** -----------------
         *  Dynamic validation
         * ----------------- */
        $rules = [];

        foreach ($fields as $field) {
            if ($field->validation_rules) {
                $rules[$field->slug] = $field->validation_rules;
            }
        }

        $validated = $request->validate($rules);

        /** -----------------
         *  Save values
         * ----------------- */
        foreach ($fields as $field) {

            // FILE
            if ($field->type === 'file' && $request->hasFile($field->slug)) {

                $path = $request->file($field->slug)
                    ->store('settings', 'public');

                AdminSettings::updateOrCreate(
                    ['slug' => $field->slug],
                    [
                        'group' => $group,
                        'value' => $path,
                    ]
                );

                continue;
            }

            // CHECKBOX (array)
            if ($field->type === 'checkbox') {
                $value = json_encode($request->input($field->slug, []));
            } else {
                $value = $request->input($field->slug);
            }

            AdminSettings::updateOrCreate(
                ['slug' => $field->slug],
                [
                    'group' => $group,
                    'value' => $value,
                ]
            );
        }

        return back()->with('success', __('Settings updated successfully'));
    }

}





