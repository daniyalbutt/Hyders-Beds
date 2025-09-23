<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'user_id',
        'product_code',
        'description',
        'price',
        'quantity',
        'total',
        'fabric_name',
        'fabric_price',
        'drawer_name',
        'drawer_price',
    ];

    protected $casts = [
        'price'    => 'float',
        'total'    => 'float',
        'quantity' => 'integer',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
