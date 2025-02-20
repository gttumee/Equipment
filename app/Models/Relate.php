<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relate extends Model
{
    protected $fillable = [
        'equipment_id',
        'name',
        'serial_number',
    ];

    public function equipment()
{
    return $this->belongsTo(Equipment::class);
}

}