<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_uniq',
        'file_id',
        'name',
        'path'
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'files_users')->withPivot('permission');
    }

    public function add()
    {

    }
}
