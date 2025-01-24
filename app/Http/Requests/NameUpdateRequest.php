<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;

class NameUpdateRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }

}
