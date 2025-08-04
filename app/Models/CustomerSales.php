<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSales extends Model
{
    protected $fillable = ['customer_id', 'sale_id'];

    use HasFactory;
}
