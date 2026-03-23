<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberStoreRequest extends FormRequest
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
            'head_of_family_id' => 'required|string|exists:head_of_families,id',
            'email' => 'required|string|email|unique:users,email',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity_number' => 'required|string|unique:family_members,identity_number',
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|unique:family_members,phone_number',
            'occupation' => 'required|string',
            'material_status' => 'required|string',
            'relation' => 'required|string|in:wife,child,husband',
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
            'relation' => 'Hubungan Keluarga',
        ];
    }
}
