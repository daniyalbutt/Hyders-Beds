<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'description',
        'sale_price',
        'volume',
        'weight',
        'width',
        'length',
        'height',
        'product_range',
        'product_section',
        'production_type',
        'status',
        'added_by'
    ];
}
