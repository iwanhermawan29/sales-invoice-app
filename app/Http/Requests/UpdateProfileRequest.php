<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    // public function authorize(): bool
    // {
    //     return auth()->check();
    // }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'        => trim((string) $this->input('name', '')),
            'agency_name' => trim((string) $this->input('agency_name', '')),
            'phone'       => preg_replace('/\s+/', '', (string) $this->input('phone', '')),
            'address'     => trim((string) $this->input('address', '')),
            'id_number'   => trim((string) $this->input('id_number', '')),
            'bank_name'   => trim((string) $this->input('bank_name', '')),
            'bank_account' => trim((string) $this->input('bank_account', '')),
        ]);
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:150'],
            'agency_name'  => ['nullable', 'string', 'max:150'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'address'      => ['nullable', 'string', 'max:255'],
            'birth_date'   => ['nullable', 'date', 'before_or_equal:today'],
            'id_number'    => ['nullable', 'string', 'max:50'],
            'bank_name'    => ['nullable', 'string', 'max:100'],
            'bank_account' => ['nullable', 'string', 'max:100'],

            // dilarang dimanipulasi agent
            'profile_status'       => ['prohibited'],
            'kode_agent'           => ['prohibited'],
            'profile_approved_by'  => ['prohibited'],
            'profile_approved_at'  => ['prohibited'],
            'profile_approval_note' => ['prohibited'],
        ];
    }

    public function attributes(): array
    {
        return [
            'agency_name' => 'nama agency',
            'birth_date'  => 'tanggal lahir',
            'id_number'   => 'nomor identitas',
        ];
    }
}
