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

    public function taskProgress()
    {
        return $this->hasMany(OrderTaskProgress::class, 'order_item_id');
    }


    public function isCompleted()
    {
        // Must have tasks first
        if ($this->taskProgress()->count() === 0) {
            return false;
        }

        // No unfinished tasks
        return $this->taskProgress()
            ->whereNull('completed_at')
            ->doesntExist();
    }


    public function currentCompletedTask()
    {
        return $this->taskProgress
            ->filter(fn ($progress) => $progress->task)
            ->sortByDesc(fn ($progress) => $progress->task->order)
            ->first();
    }

    public function getCurrentTaskAttribute()
    {
        return $this->currentCompletedTask();
    }

}
