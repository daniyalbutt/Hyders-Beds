<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer',
        'address',
        'order_date',
        'order_reference',
        'order_type',
        'required_date',
        'salesperson_one',
        'salesperson_two',
        'customer_contact',
        'status',
        'subtotal',
        'vat',
        'deposit_total',
        'grand_total',
        'added_by',
        'draft',
        'route_id'
    ];

    public function get_customer(){
        return $this->hasOne(Customer::class, 'id', 'customer');
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function deposits(){
        return $this->hasMany(Deposit::class);
    }

    public function recalcTotals(){
        $subtotal = $this->items()->sum('total');
        $depositTotal = $this->deposits()->sum('amount');
        $netTotal = $subtotal - $depositTotal;
        $vat = $netTotal * 0.20;
        $grandTotal = $netTotal + $vat;
        $this->update([
            'subtotal'      => $subtotal,
            'vat'           => $vat,
            'deposit_total' => $depositTotal,
            'grand_total'   => $grandTotal,
        ]);
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }


}
