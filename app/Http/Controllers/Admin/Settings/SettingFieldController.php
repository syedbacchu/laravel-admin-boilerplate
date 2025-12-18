<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\SettingsField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingFieldController extends Controller
{
    public function index()
    {
        $fields = SettingsField::orderBy('group')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group');

        return view('admin.settings.fields.index', compact('fields'));
    }

    public function create()
    {
        return view('admin.settings.fields.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'group' => 'required|string|max:50',
            'label' => 'required|string|max:100',
            'slug'  => 'required|string|unique:settings_fields,slug',
            'type'  => 'required|in:text,password,select,file,number,checkbox,radio,textarea',
            'options' => 'nullable|string',
            'validation_rules' => 'nullable|string',
        ]);

        $data = [
            'group' => Str::slug($request->group),
            'label' => $request->label,
            'slug'  => Str::slug($request->slug, '_'),
            'type'  => $request->type,
            'validation_rules' => $request->validation_rules,
        ];

        if (in_array($request->type, ['select', 'radio','checkbox'])) {
            $data['options'] = array_map('trim', explode(',', $request->options));
        } else {
            $data['options'] = null;
        }
        SettingsField::create($data);

        return redirect()
            ->route('settings.fields.index')
            ->with('success', 'Setting field created');
    }
    public function edit(SettingsField $field)
    {
        return view('admin.settings.fields.edit', compact('field'));
    }

    public function update(Request $request, SettingsField $field)
    {
        $request->validate([
            'group' => 'required|string|max:50',
            'label' => 'required|string|max:100',
            'slug'  => 'required|string|unique:settings_fields,slug,' . $field->id,
            'type'  => 'required|in:text,password,select,file,number,checkbox,radio,textarea',
            'options' => 'nullable|string',
            'validation_rules' => 'nullable|string',
        ]);

        $data = [
            'group' => Str::slug($request->group),
            'label' => $request->label,
            'slug'  => Str::slug($request->slug, '_'),
            'type'  => $request->type,
            'validation_rules' => $request->validation_rules,
        ];

        if (in_array($request->type, ['select', 'radio','checkbox'])) {
            $data['options'] = array_map('trim', explode(',', $request->options));
        } else {
            $data['options'] = null;
        }
        $field->update($data);

        return redirect()->route('settings.fields.index')
            ->with('success', 'Setting field updated');
    }

    public function destroy(SettingsField $field)
    {
        $field->delete();

        return back()->with('success', 'Setting field deleted');
    }
}
