<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya admin yang boleh mengubah user
        return $this->user()->isRole('admin');
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name'                  => 'required|string|max:255',
            'email'                 => "required|email|unique:users,email,{$userId}",
            'password'              => 'nullable|string|min:8|confirmed',
            'role_id'               => 'required|exists:roles,id',
        ];
    }
}
