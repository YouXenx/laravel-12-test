<?php

namespace App\Http\Requests;

use App\Models\FamilyMember;
use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberUpdateRequest extends FormRequest
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
            'password' => 'nullable|string|min:8',
            'email' => 'required|string|email|unique:users,email,' . optional(
                FamilyMember::find($this->route('family_member'))
            )->user_id,

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
            'Relation' => 'Hubungan',
        ];
    }
}
