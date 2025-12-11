<?php

namespace App\Traits;


use App\Models\CustomField;
use App\Models\CustomFieldValue;


trait HasCustomFields
{
    public function customFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }


// eager-load helper: returns associative array name=>value
    public function getCustomFieldsAttribute()
    {
        $fields = $this->customFieldValues()->with('field')->get();
        $result = [];


        foreach ($fields as $fv) {
            if ($fv->field) {
                $result[$fv->field->name] = $fv->value;
            }
        }


        return $result;
    }


    public function getCustomFieldValue(string $name, $default = null)
    {
        $field = CustomField::where('module', $this->getTable())->where('name', $name)->first();
        if (! $field) return $default;


        $value = $this->customFieldValues()->where('custom_field_id', $field->id)->value('value');


        return $value ?? $field->default_value ?? $default;
    }


    public function setCustomFieldValue(string $name, $value)
    {
        $field = CustomField::where('module', $this->getTable())->where('name', $name)->first();
        if (! $field) return false;


        return CustomFieldValue::updateOrCreate([
            'custom_field_id' => $field->id,
            'model_type' => get_class($this),
            'model_id' => $this->getKey(),
        ], [
            'value' => is_null($value) ? null : (is_array($value) ? json_encode($value) : (string)$value),
        ]);
    }
}
