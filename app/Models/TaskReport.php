<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'note',
        'proof_assignment',
        'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
