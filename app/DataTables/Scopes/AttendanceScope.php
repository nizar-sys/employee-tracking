<?php

namespace App\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

class AttendanceScope implements DataTableScope
{
    public function __construct(protected $request) {}

    public function apply($query)
    {
        return $query->when($this->request->filled('status'), function ($query) {
            $query->where('status', $this->request->status);
        })->when($this->request->filled('employee_id'), function ($query) {
            $query->where('employee_id', $this->request->employee_id);
        });
    }
}
