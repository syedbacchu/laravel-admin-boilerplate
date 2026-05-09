<?php

namespace App\Http\Requests\AboutCompany;

use Illuminate\Foundation\Http\FormRequest;

class AboutCompanyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'site_type' => 'required|integer|in:1,2,3,4,5',
            'banner_image' => 'nullable|string|max:500',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'our_story' => 'nullable|string',
            'story_image' => 'nullable|string|max:500',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'core_values' => 'nullable|array',
            'core_values.*.image' => 'nullable|string|max:500',
            'core_values.*.title' => 'nullable|string|max:255',
            'core_values.*.subtitle' => 'nullable|string|max:500',
            'core_values.*.description' => 'nullable|string',
            'core_values.*.sort_order' => 'nullable|integer|min:0',
            'company_stats' => 'nullable|array',
            'company_stats.*.image' => 'nullable|string|max:500',
            'company_stats.*.title' => 'nullable|string|max:255',
            'company_stats.*.subtitle' => 'nullable|string|max:500',
            'company_stats.*.description' => 'nullable|string',
            'company_stats.*.sort_order' => 'nullable|integer|min:0',
            'why_choose' => 'nullable|array',
            'why_choose.*.image' => 'nullable|string|max:500',
            'why_choose.*.title' => 'nullable|string|max:255',
            'why_choose.*.subtitle' => 'nullable|string|max:500',
            'why_choose.*.description' => 'nullable|string',
            'why_choose.*.sort_order' => 'nullable|integer|min:0',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'site_type' => (int) ($this->site_type ?? 1),
            'core_values' => is_string($this->core_values)
                ? json_decode($this->core_values, true)
                : ($this->core_values ?: null),
            'company_stats' => is_string($this->company_stats)
                ? json_decode($this->company_stats, true)
                : ($this->company_stats ?: null),
            'why_choose' => is_string($this->why_choose)
                ? json_decode($this->why_choose, true)
                : ($this->why_choose ?: null),
        ]);
    }
}
