<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Kelas::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:10',
                'unique:kelas,nama',
                'regex:/^[1-6][A-Z]$/', // Format: 1A, 2B, 6C, etc.
            ],
            'tingkat_kelas' => [
                'required',
                'integer',
                'min:1',
                'max:6',
            ],
            'wali_kelas_id' => [
                'required',
                'exists:users,id',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kelas wajib diisi.',
            'nama.unique' => 'Nama kelas sudah digunakan.',
            'nama.regex' => 'Format nama kelas harus berupa angka 1-6 diikuti huruf A-Z (contoh: 1A, 6B).',
            'tingkat_kelas.required' => 'Tingkat kelas wajib diisi.',
            'tingkat_kelas.min' => 'Tingkat kelas minimal 1.',
            'tingkat_kelas.max' => 'Tingkat kelas maksimal 6.',
            'wali_kelas_id.required' => 'Wali kelas wajib dipilih.',
            'wali_kelas_id.exists' => 'Wali kelas yang dipilih tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama kelas',
            'tingkat_kelas' => 'tingkat kelas',
            'wali_kelas_id' => 'wali kelas',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Extract tingkat_kelas from nama if not provided
        if ($this->nama && !$this->tingkat_kelas) {
            $firstChar = substr($this->nama, 0, 1);
            if (is_numeric($firstChar) && $firstChar >= 1 && $firstChar <= 6) {
                $this->merge([
                    'tingkat_kelas' => (int) $firstChar,
                ]);
            }
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check if wali kelas is a teacher
            if ($this->wali_kelas_id) {
                $user = \App\Models\User::find($this->wali_kelas_id);
                if ($user && !$user->hasRole('Guru')) {
                    $validator->errors()->add('wali_kelas_id', 'Wali kelas harus berperan sebagai guru.');
                }
            }

            // Validate that tingkat_kelas matches the first character of nama
            if ($this->nama && $this->tingkat_kelas) {
                $firstChar = substr($this->nama, 0, 1);
                if ((string)$this->tingkat_kelas !== $firstChar) {
                    $validator->errors()->add('nama', 'Tingkat kelas tidak sesuai dengan nama kelas.');
                }
            }
        });
    }
}
