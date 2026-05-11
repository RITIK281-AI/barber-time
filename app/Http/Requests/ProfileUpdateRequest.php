<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone'            => ['nullable', 'string', 'max:20'],
            'address'          => ['nullable', 'string', 'max:255'],
            'profile_photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            // password fields only required when current_password is provided
            'current_password' => ['nullable', 'string'],
            'password'         => [
                'nullable',
                'confirmed',
                Password::min(9)->numbers()->symbols()->rules(['regex:/[A-Z]/']),
            ],
        ];
    }
}
