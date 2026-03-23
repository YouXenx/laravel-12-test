<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SocialAssistanceUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'thumbnail' => 'nullable|image|max:2048',
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:staple,cash,subsidized fuel,health,education',
            'amount' => 'required|numeric|min:0',
            'provider' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_available' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail' => 'thumbnail',
            'name' => 'name',
            'category' => 'category',
            'amount' => 'amount',
            'provider' => 'provider',
            'description' => 'description',
            'is_available' => 'availability status',
        ];
    }
}
