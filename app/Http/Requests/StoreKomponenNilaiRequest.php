<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKomponenNilaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization akan dicek di policy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'penugasan_mengajar_id' => 'required|exists:penugasan_mengajars,id',
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:tugas,uh,uts,uas',
            'bobot' => 'required|integer|min:1|max:100',
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
            'nama.required' => 'Nama komponen nilai harus diisi.',
            'nama.max' => 'Nama komponen nilai maksimal 255 karakter.',
            'jenis.required' => 'Jenis komponen nilai harus dipilih.',
            'jenis.in' => 'Jenis komponen nilai tidak valid.',
            'bobot.required' => 'Bobot harus diisi.',
            'bobot.integer' => 'Bobot harus berupa angka.',
            'bobot.min' => 'Bobot minimal 1.',
            'bobot.max' => 'Bobot maksimal 100.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'penugasan_mengajar_id' => 'penugasan mengajar',
            'nama' => 'nama komponen',
            'jenis' => 'jenis komponen',
            'bobot' => 'bobot nilai',
        ];
    }
}
