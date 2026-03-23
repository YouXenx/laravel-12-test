<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeadOfFamilyStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|unique:users,email',

            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity_number' => 'required|string|unique:head_of_families,identity_number',
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|unique:head_of_families,phone_number',
            'occupation' => 'required|string',
            'material_status' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'password' => 'Kata Sandi',
            'email' => 'Email',
            'profile_picture' => 'Foto Profil',
            'identity_number' => 'Nomor Identitas',
            'gender' => 'Jenis Kelamin',
            'date_of_birth' => 'Tanggal Lahir',
            'phone_number' => 'Nomor Telepon',
            'occupation' => 'Pekerjaan',
            'material_status' => 'Status Perkawinan',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah digunakan.',
            'min' => ':attribute minimal :min karakter.',
            'email' => ':attribute harus berupa email yang valid.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berupa file dengan tipe: :values.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'max:2048' => ':attribute maksimal berukuran :max kilobytes.',
            'unique:users' => ':attribute sudah terdaftar pada pengguna lain.',
            'in'    => ':attribute harus salah satu dari berikut: :values.',
        ];
    }
}
