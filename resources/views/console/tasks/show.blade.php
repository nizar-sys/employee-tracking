@extends('layouts.app')
@section('title', 'Task Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded">

            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Task Details</h5>
                <a href="{{ route('tasks.index') }}" class="btn btn-primary">Back to List</a>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="task-details">
                            <h6 class="card-subtitle mt-1 text-muted">Task Information</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Employee ID:</strong> {{ $task->employee_id }}</li>
                                <li class="mb-2"><strong>Title:</strong> {{ $task->title }}</li>
                                <li class="mb-2"><strong>Date:</strong>
                                    {{ \Carbon\Carbon::parse($task->date)->format('d-m-Y') }}</li>
                                <li class="mb-2"><strong>Coordinates:</strong> {{ $task->longlat }}</li>
                                <li class="mb-2"><strong>Description:</strong> {{ $task->description }}</li>
                                <li class="mb-2"><strong>Location Validated:</strong>
                                    {{ $task->is_validate_location ? 'Yes' : 'No' }}</li>
                                <li><strong>Last Updated:</strong>
                                    {{ \Carbon\Carbon::parse($task->updated_at)->format('d-m-Y H:i') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/console/tasks/show-script.js')
@endpush
