<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'time',
        'check_in',
        'check_out',
        'location_check_in',
        'location_check_out',
        'longlat_check_in',
        'longlat_check_out',
        'is_valid_location_check_in',
        'is_valid_location_check_out',
        'picture_check_in',
        'picture_check_out',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
