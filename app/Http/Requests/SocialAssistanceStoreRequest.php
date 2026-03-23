<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAssistanceStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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

    public function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'numeric' => ':attribute harus berupa angka.',
            'image' => ':attribute harus berupa gambar.',
            'max:2048' => ':attribute maksimal berukuran :max kilobytes.',
            'in' => ':attribute harus salah satu dari berikut: :values.',
            'boolean' => ':attribute harus berupa boolean.',
            'exists' => ':attribute harus ada di database.',
            'unique' => ':attribute sudah ada di database.',
            'nullable' => ':attribute boleh diisi.',
            'email' => ':attribute harus berupa email yang valid.',
            'max' => ':attribute maksimal :max.',
            'min' => ':attribute minimal :min.',
        ];
    }

}
