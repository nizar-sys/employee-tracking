@extends('layouts.app')
@section('title', 'Create Task')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">
                    <span class="fw-normal">Add Task</span>
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

                    <form class="task pt-0" id="taskForm" method="POST" action="{{ route('tasks.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <select class="form-select @error('employee_id') is-invalid @enderror"
                                        id="task-employee-id" name="employee_id" aria-label="Employee">
                                        <option value="" disabled selected>Select Employees</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->user->name }} <small>({{ $employee->number }})</small>
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="task-employee-id">Employee</label>
                                    @error('employee_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="task-title" name="title" placeholder="Title" aria-label="Title"
                                        value="{{ old('title') }}" />
                                    <label for="task-title">Title</label>
                                    @error('title')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="task-date" name="date" aria-label="Date" value="{{ old('date') }}" />
                                    <label for="task-date">Date</label>
                                    @error('date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="text" class="form-control @error('longlat') is-invalid @enderror"
                                        id="task-longlat" name="longlat" placeholder="Longitude/Latitude"
                                        aria-label="Longitude/Latitude" value="{{ old('longlat') }}" />
                                    <label for="task-longlat">Longitude/Latitude</label>
                                    @error('longlat')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Description"
                                        name="description" aria-label="Description" cols="30" rows="10">{{ old('description') }}</textarea>
                                    <label for="description">Description</label>
                                    @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="text" class="form-control @error('type') is-invalid @enderror"
                                        id="task-type" name="type" placeholder="Type" aria-label="Type"
                                        value="{{ old('type') }}" />
                                    <label for="task-type">Type</label>
                                    @error('type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <label class="form-label text-light fw-medium d-block mb-3" for="is_validate_location">Is
                                    Location Valid
                                    ?</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_validate_location"
                                        id="isLateYes" value="yes">
                                    <label class="form-check-label" for="isLateYes">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_validate_location" checked
                                        id="isLateNo" value="no">
                                    <label class="form-check-label" for="isLateNo">No</label>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    @vite('resources/js/console/tasks/validation_script.js')
@endpush
