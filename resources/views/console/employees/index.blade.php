@extends('layouts.app')
@section('title', 'Employees')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Filters</h5>
                <div class="d-flex justify-content-start align-items-center row gx-5 pt-4 gap-5 gap-md-0">
                    <div class="col-md-4 designation_id_filter">
                        <select id="designation_id_filter" class="form-select" data-filter="designation_id"
                            name="designation_id_filter">
                            <option value="">All Designations</option>
                            @foreach ($designations as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
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
        var urlDelete = "{{ route('employees.destroy', ':id') }}";
    </script>
    @vite('resources/js/console/employees/script.js')
@endpush
