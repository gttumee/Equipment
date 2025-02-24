<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $fillable = [
        'user_id',
        'equipment_id',
        'status',
        'register_date',
        'reason'
    ];
      
    public function equipment()
    {
        return $this->belongsTo(Equipment::class); 
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }

}