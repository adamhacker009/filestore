<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilesUser extends Model
{
    protected $fillable = [
        'user_id',
        'file_id',
        'permission',
    ];
}
