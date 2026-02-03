<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTaskProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'task_name_id',
        'user_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(TaskName::class, 'task_name_id');
    }


}
