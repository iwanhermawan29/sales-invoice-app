<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContestRequest extends FormRequest
{
    public function authorize(): bool
    {
        // ganti sesuai policy/guard Anda
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kontes'     => ['required', 'string', 'max:150'],
            'periode'         => ['nullable', 'in:monthly,quarterly,annual'],
            'target_premi'    => ['required', 'numeric', 'min:0'],
            'target_case'     => ['required', 'integer', 'min:0'],
            'tanggal_mulai'   => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            // flyer opsional saat update; hanya validasi jika diunggah
            'flyer'           => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_kontes'     => 'Nama Kontes',
            'target_premi'    => 'Target Premi',
            'target_case'     => 'Target Case',
            'tanggal_mulai'   => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'flyer'           => 'Flyer',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'flyer.mimes' => 'Flyer harus berupa file: JPG, JPEG, PNG, atau PDF.',
            'flyer.max'   => 'Ukuran flyer maksimum 2MB.',
        ];
    }
}
