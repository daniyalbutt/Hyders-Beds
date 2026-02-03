<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskNameProductionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name_id',
        'production_type',
        'order_number',
    ];

    public function taskName()
    {
        return $this->belongsTo(TaskName::class);
    }
}
