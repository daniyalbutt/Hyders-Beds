<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeSendToProduction($query)
    {
        return $query->where('send_to_production', 1);
    }

    public function scopeForProductionUser($query)
    {
        if (auth()->check() && auth()->user()->hasRole('production')) {
            return $query->whereIn(
                'task_name_id',
                auth()->user()->tasks()->pluck('task_names.id')
            );
        }

        return $query;
    }


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

    public function isCompleted()
    {
        return $this->items->every(fn ($item) => $item->isCompleted());
    }

    public function taskProgress()
    {
        return $this->hasMany(OrderTaskProgress::class);
    }

    public function currentStatus()
    {
        if ($this->isCompleted()) {
            return 'Completed';
        }

        $lastTask = $this->items
            ->map(fn($item) => $item->currentCompletedTask())
            ->filter()
            ->sortByDesc(fn($progress) => $progress->task->order ?? 0)
            ->first();

        return $lastTask ? $lastTask->task->name : 'Not started';
    }



    public function getStatusAttribute()
    {
        return $this->currentStatus();
    }




}
