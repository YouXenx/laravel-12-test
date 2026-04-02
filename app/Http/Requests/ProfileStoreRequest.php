<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileStoreRequest extends FormRequest
{
    /**
     * Authorize the request
     */
    public function authorize(): bool
    {
        return true; // wajib true supaya validasi dijalankan
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'about' => 'required|string',
            'headman' => 'required|string',
            'people' => 'required|integer',
            'agricultural_area' => 'required|integer',
            'total_area' => 'required|integer',
            'profile_images' => 'nullable|array',
            'profile_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Custom attribute names
     */
    public function attributes()
    {
        return [
            'thumbnail' => 'Thumbnail',
            'name' => 'Nama',
            'about' => 'Tentang',
            'headman' => 'Kepala Keluarga',
            'people' => 'Jumlah Penduduk',
            'agricultural_area' => 'Luas Lahan Pertanian',
            'total_area' => 'Luas Total',
            'profile_images' => 'Foto Profil',
            'profile_images.*' => 'Foto Profil',
        ];
    }
}