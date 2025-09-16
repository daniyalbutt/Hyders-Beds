<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function get_customer(){
        return $this->hasOne(Customer::class, 'id', 'customer');
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

}
