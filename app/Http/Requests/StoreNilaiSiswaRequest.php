<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNilaiSiswaRequest extends FormRequest
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
            'komponen_nilai_id' => 'required|exists:komponen_nilais,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'komponen_nilai_id.required' => 'Komponen nilai harus dipilih.',
            'komponen_nilai_id.exists' => 'Komponen nilai tidak valid.',
            'nilai.required' => 'Data nilai siswa harus diisi.',
            'nilai.array' => 'Data nilai siswa harus berupa array.',
            'nilai.*.numeric' => 'Nilai siswa harus berupa angka.',
            'nilai.*.min' => 'Nilai siswa minimal 0.',
            'nilai.*.max' => 'Nilai siswa maksimal 100.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'komponen_nilai_id' => 'komponen nilai',
            'nilai' => 'nilai siswa',
            'nilai.*' => 'nilai siswa',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Filter out empty values but keep 0 as valid
        if ($this->has('nilai') && is_array($this->nilai)) {
            $filteredNilai = array_filter($this->nilai, function ($value) {
                return $value !== null && $value !== '';
            });
            $this->merge(['nilai' => $filteredNilai]);
        }
    }
}
