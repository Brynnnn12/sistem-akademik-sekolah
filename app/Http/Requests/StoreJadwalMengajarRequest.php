<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalMengajarRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'penugasan_mengajar_id' => 'required|exists:penugasan_mengajars,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i|before:jam_selesai',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'penugasan_mengajar_id.required' => 'Penugasan mengajar harus dipilih.',
            'penugasan_mengajar_id.exists' => 'Penugasan mengajar tidak valid.',
            'hari.required' => 'Hari harus dipilih.',
            'hari.in' => 'Hari tidak valid.',
            'jam_mulai.required' => 'Jam mulai harus diisi.',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid.',
            'jam_mulai.before' => 'Jam mulai harus sebelum jam selesai.',
            'jam_selesai.required' => 'Jam selesai harus diisi.',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ];
    }
}
