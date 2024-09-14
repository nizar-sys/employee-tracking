@extends('layouts.app')
@section('title', 'Leaves')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Filters</h5>
                <div class="d-flex justify-content-start align-items-center row gx-5 pt-4 gap-5 gap-md-0">
                    <div class="col-md-4 employee_id_filter">
                        <select id="employee_id_filter" class="form-select" data-filter="employee_id" name="employee_id_filter">
                            <option value="">All Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->user->name }}
                                    <small>({{ $employee->number }})</small></option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 leave_type_filter">
                        <select id="leave_type_filter" class="form-select" data-filter="leave_type"
                            name="leave_type_filter">
                            <option value="">All Types</option>
                            @foreach ($leaveTypes as $leaveType)
                                <option value="{{ $leaveType }}">{{ ucfirst($leaveType) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 leave_status_filter">
                        <select id="leave_status_filter" class="form-select" data-filter="leave_status"
                            name="leave_status_filter">
                            <option value="">All Status</option>
                            @foreach ($leaveStatuses as $leaveStatus)
                                <option value="{{ $leaveStatus }}">{{ ucfirst($leaveStatus) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                {{ $dataTable->table(['class' => 'datatables-permissions table']) }}
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        var urlDelete = "{{ route('leaves.destroy', ':id') }}";
    </script>
    @vite('resources/js/console/leaves/script.js')
@endpush
