@extends('layouts.app')
@section('title', 'Create Task Report')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">
                    <span class="fw-normal">Add Task Report</span>
                </h5>
            </div>

            <div class="card-body">
                <div class="offcanvas-body mx-0 flex-grow-0 h-100 mt-2">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <h4 class="alert-heading d-flex align-items-center">
                                <span class="alert-icon rounded">
                                    <i class="ri-error-warning-line ri-22px"></i>
                                </span>
                                Something went wrong!
                            </h4>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form class="task pt-0" id="taskReportForm" method="POST" action="{{ route('task-reports.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <select class="form-select @error('task_id') is-invalid @enderror"
                                        id="task-report-task-id" name="task_id" aria-label="Employee">
                                        <option value="" disabled selected>Select Task</option>
                                        @foreach ($tasks as $task)
                                            <option value="{{ $task->id }}"
                                                {{ old('task_id') == $task->id ? 'selected' : '' }}>
                                                {{ $task->title }}</small>
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="task-report-task-id">Task</label>
                                    @error('task_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                        id="task-report-employee-id" name="employee_id" placeholder="Employee"
                                        aria-label="Employee" disabled>
                                    <label for="task-report-employee-id">Employee</label>
                                    @error('employee_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" aria-label="Status">
                                        <option value="" disabled selected>Select Status</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}</small>
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="status">Status</label>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="file"
                                        class="form-control @error('proof_assignment') is-invalid @enderror"
                                        id="proof-assignment" name="proof_assignment" aria-label="Proof Assignment" />
                                    <label for="attendance-picture-check-in">Proof Assignment</label>
                                    @error('proof_assignment')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" placeholder="Note" name="note"
                                        aria-label="Note" cols="30" rows="10">{{ old('note') }}</textarea>
                                    <label for="note">Note</label>
                                    @error('note')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <a href="{{ route('task-reports.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const taskSelect = document.getElementById("task-report-task-id");
            const employeeField = document.getElementById("task-report-employee-id");

            const baseUrl =
                "{{ route('task.employees.get', ['task' => '__TASK_ID__']) }}";

            taskSelect.addEventListener("change", function() {
                const taskId = taskSelect.value;

                if (taskId) {
                    const url = baseUrl.replace("__TASK_ID__", taskId);

                    fetch(url)
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.name) {
                                employeeField.value = data.name;
                            } else {
                                employeeField.value = ""; // Clear if no employee found
                            }
                        })
                        .catch((error) =>
                            console.error("Error fetching employee:", error)
                        );
                } else {
                    employeeField.value = "";
                }
            });
        });
    </script>
    @vite('resources/js/console/task-reports/validation_script.js')
@endpush
