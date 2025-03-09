<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'buy_date',
        'end_date',
        'status',
        'password',
        'user_id',
        'percentage',
        'location',
        'owner',
        'code',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($equipment) {
            $created_at = Carbon::parse($equipment->created_at);
            $code = $created_at->format('ymdHis');  // Format: YYMMDDHHMMSS
            $equipment->code = $code ;
        });
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function relate()
{
    return $this->hasMany(Relate::class);
}

public function reason()
{
    return $this->hasOne(Reason::class);
}


}