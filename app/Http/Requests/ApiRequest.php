<?php

namespace App\Http\Requests;

use App\Exceptions\ApiValidateException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            // no
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ApiValidateException(422, false, 'Validation error', $validator->errors());
    }
}
