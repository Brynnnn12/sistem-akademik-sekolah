<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTahunAjaranRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'unique:tahun_ajarans,nama'],
            'semester' => ['required', 'in:ganjil,genap'],
            'aktif' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama tahun ajaran wajib diisi.',
            'nama.string' => 'Nama tahun ajaran harus berupa teks.',
            'nama.unique' => 'Nama tahun ajaran sudah digunakan.',
            'semester.required' => 'Semester wajib diisi.',
            'semester.in' => 'Semester harus bernilai ganjil atau genap.',
            'aktif.required' => 'Status aktif wajib diisi.',
            'aktif.boolean' => 'Status aktif harus bernilai true atau false.',
        ];
    }
}
