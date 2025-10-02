<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $casts = [
        'start_time' => 'datetime:H:i',
    ];

    protected $fillable = [
        'name',
        'start_date',
        'start_time',
        'start_location',
        'end_location'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
