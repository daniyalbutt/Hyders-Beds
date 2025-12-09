<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskName extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'allow_next_step',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }
}