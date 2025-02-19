<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function register()
    {
        return $this->belongsTo(register::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }


}