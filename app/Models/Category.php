<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use app\Models\Equipment;

class Category extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    public function equiment()
    {
        return $this->hasMany(Equipment::class);
    }
    
}