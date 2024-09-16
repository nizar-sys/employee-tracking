<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'title',
        'description',
        'date',
        'longlat',
        'is_validate_location',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function taskReport()
    {
        return $this->hasOne(TaskReport::class, 'task_id');
    }
}
