<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Biodata dari profiles
            'nip' => ['nullable', 'string', 'unique:profiles,nip,' . ($this->user()->profile->id ?? 'NULL')],
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'jenis_kelamin' => ['nullable', 'in:laki-laki,perempuan'],
            'photo' => ['nullable', 'image', 'max:2048'], // Max 2MB

            // Dari users
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'nip.string' => 'NIP harus berupa teks.',
            'nip.unique' => 'NIP sudah digunakan.',
            'no_hp.string' => 'No HP harus berupa teks.',
            'no_hp.max' => 'No HP maksimal 20 karakter.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'jenis_kelamin.in' => 'Jenis kelamin harus laki-laki atau perempuan.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.max' => 'Foto maksimal 2MB.',
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.lowercase' => 'Email harus huruf kecil.',
            'email.email' => 'Email harus format email yang valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan.',
        ];
    }
}
