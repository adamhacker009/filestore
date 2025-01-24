<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => ['required','unique:users', 'email'],
            'password' => ['required',
                Password::min(3)
                    ->mixedCase()
                    ->numbers()],

        ];
    }
}
