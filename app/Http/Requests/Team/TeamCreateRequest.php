<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id') ?? $this->input('edit_id');

        return [
            'name' => ['required', 'string', 'max:255'],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('teams', 'slug')->ignore($id),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'designation' => ['nullable', 'string', 'max:255'],
            // Social links
            'facebook_url' => ['nullable', 'url'],
            'twitter_url' => ['nullable', 'url'],
            'linkedin_url' => ['nullable', 'url'],
            'instagram_url' => ['nullable', 'url'],
            'github_url' => ['nullable', 'url'],
            'youtube_url' => ['nullable', 'url'],
            'join_date' => ['nullable', 'date'],
            'site_type' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'boolean'],
        ];
    }
}