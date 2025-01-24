<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;

class FileRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'file' => 'required',
            'file.*' => 'mimes:doc,pdf,docx,zip,jpeg,jpg,png|max:2048'
        ];
    }

}
