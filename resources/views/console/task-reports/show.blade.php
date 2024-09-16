@extends('layouts.app')
@section('title', 'Task Report Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">
                    <span class="fw-normal">Task Report Details</span>
                </h5>
            </div>

            <div class="card-body">
                <div class="offcanvas-body mx-0 flex-grow-0 h-100 mt-2">

                    <!-- Display Error Messages if any -->
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

                    <!-- Task Report Details -->
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-floating form-floating-outline mb-5 mt-2">
                                <input type="text" class="form-control" id="task-title" name="task_title"
                                    value="{{ $taskReport->task->title }}" disabled>
                                <label for="task-title">Task</label>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="form-floating form-floating-outline mb-5 mt-2">
                                <input type="text" class="form-control" id="employee-name" name="employee_name"
                                    value="{{ $taskReport->task->employee->user->name }}" disabled>
                                <label for="employee-name">Employee</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-floating form-floating-outline mb-5 mt-2">
                                <input type="text" class="form-control" id="status" name="status"
                                    value="{{ ucfirst($taskReport->status) }}" disabled>
                                <label for="status">Status</label>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="form-floating form-floating-outline mb-5 mt-2">
                                @if ($taskReport->proof_assignment)
                                    <a href="{{ asset($taskReport->proof_assignment) }}" class="btn btn-primary" download>
                                        Download Proof Assignment
                                    </a>
                                @else
                                    <p>No proof assignment uploaded.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-floating form-floating-outline mb-5 mt-2">
                                <textarea class="form-control" id="note" name="note" disabled cols="30" rows="10">{{ $taskReport->note }}</textarea>
                                <label for="note">Note</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <a href="{{ route('task-reports.index') }}" class="btn btn-outline-secondary">Back to List</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
