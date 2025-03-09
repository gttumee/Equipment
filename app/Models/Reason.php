<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $fillable = [
        'reason',
        'user_id',
        'equipment_id'
    ];
    
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}