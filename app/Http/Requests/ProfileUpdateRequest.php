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
    // app/Http/Requests/ProfileUpdateRequest.php
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:150'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email,' . $this->user()->id],
            'agency_name'  => ['nullable', 'string', 'max:150'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'address'      => ['nullable', 'string', 'max:255'],
            'birth_date'   => ['nullable', 'date', 'before_or_equal:today'],
            'id_number'    => ['nullable', 'string', 'max:50'],
            'bank_name'    => ['nullable', 'string', 'max:100'],
            'bank_account' => ['nullable', 'string', 'max:100'],

            // dilarang dimanipulasi dari form agent
            'profile_status'        => ['prohibited'],
            'kode_agent'            => ['prohibited'],
            'profile_approved_by'   => ['prohibited'],
            'profile_approved_at'   => ['prohibited'],
            'profile_approval_note' => ['prohibited'],
        ];
    }
}
