<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function equiment()
    {
        return $this->hasMany(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }

}