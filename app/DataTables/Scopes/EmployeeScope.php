<?php

namespace App\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

class EmployeeScope implements DataTableScope
{

    public function __construct(protected $request)
    {
    }

    public function apply($query)
    {
        return $query->when($this->request->designation_id, function ($query) {
            return $query->where('designation_id', $this->request->designation_id);
        });
    }
}
