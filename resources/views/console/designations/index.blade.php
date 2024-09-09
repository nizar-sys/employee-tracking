@extends('layouts.app')
@section('title', 'Designations')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-datatable table-responsive">
                {{ $dataTable->table(['class' => 'datatables-permissions table']) }}
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        var urlDelete = "{{ route('designations.destroy', ':id') }}";
    </script>
    @vite('resources/js/console/designations/script.js')
@endpush
