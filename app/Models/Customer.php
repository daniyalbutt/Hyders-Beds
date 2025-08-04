<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function sales(){
        return $this->hasMany(CustomerSales::class, 'customer_id');
    }

    public function trades(){
        return $this->hasMany(CustomerTrade::class, 'customer_id');
    }

    public function bank(){
        return $this->hasOne(CustomerBank::class, 'customer_id');
    }

    public function limited(){
        return $this->hasOne(CustomerLimited::class, 'customer_id');
    }

    public function partners(){
        return $this->hasMany(CustomerPartnership::class, 'customer_id');
    }
}
