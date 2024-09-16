<?php

namespace App\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

class TaskReportScope implements DataTableScope
{
    public function __construct(protected $request) {}

    public function apply($query)
    {
        return $query
            ->when($this->request->filled('employee_id'), function ($query) {
                $query->whereHas('task', function ($q) {
                    $q->where('employee_id', $this->request->employee_id);
                });
            })
            ->when($this->request->filled('status'), function ($query) {
                $query->where('status', $this->request->status);
            });
    }
}
