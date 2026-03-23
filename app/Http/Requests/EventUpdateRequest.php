<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */

     public function rules(): array
     {
         return [
             'thumbnail' => 'nullable|image|mimes:png,jpg',
             'name' => 'required|string',
             'description' => 'required|string',
             'price' => 'required|integer',
             'date' => 'required|date',
             'time' => 'required',
             'is_active' => 'boolean'
         ];
     }
 
     public function attributes()
     {
         return [
             'thumbnail' => 'Thumbnail',
             'name' => 'Name',
             'description' => 'Deskripsi',
             'price' => 'Harga',
             'date' => 'Tanggal',
             'time' => 'Waktu',
             'is_active' => 'Aktif'
         ];
     }
}
