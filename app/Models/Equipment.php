<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'buy_date',
        'status',
        'password',
        'user_id',
        'percentage',
        'location',
        'owner',
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

    public function relate()
{
    return $this->hasMany(Relate::class);
}


}