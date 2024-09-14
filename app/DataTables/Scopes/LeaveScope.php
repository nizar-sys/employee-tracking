<?php

namespace App\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

class LeaveScope implements DataTableScope
{
    public function __construct(protected $request)
    {
    }

    public function apply($query)
    {
        return $query->when($this->request->filled('leave_type'), function ($query) {
            $query->where('leave_type', $this->request->leave_type);
        })->when($this->request->filled('status'), function ($query) {
            $query->where('status', $this->request->status);
        })->when($this->request->filled('employee_id'), function ($query) {
            $query->where('employee_id', $this->request->employee_id);
        });
    }
}
