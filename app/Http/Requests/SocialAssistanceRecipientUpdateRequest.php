<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SocialAssistanceRecipientUpdateRequest extends FormRequest
{
    /*
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'social_assistance_id' => 'required|exists:social_assistances,id',
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string',
            'bank' => 'required|string|in:bri,bni,bca,mandiri',
            'account_number' => 'required|integer',
            'proof' => 'nullable|image',
            'status' => 'nullable|string|in:pending,approved,rejected'
        ];
    }

    public function attributes()
    {
        return [
            'social_assistance_id' => 'Bantuan Sosial',
            'head_of_family_id' => 'Kepala Keluarga',
            'amount' => 'Jumlah',
            'reason' => 'Alasan',
            'bank' => 'Bank',
            'account_number' => 'Nomor Rekening',
            'proof' => 'Bukti Penerimaan',
            'status' => 'Status Pengajuan',
        ];
    }
}
