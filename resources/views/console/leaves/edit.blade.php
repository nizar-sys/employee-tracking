@extends('layouts.app')
@section('title', 'Edit Leave')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">
                    <span class="fw-normal">Edit Leave</span>
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

                    <form class="leave pt-0" id="leaveForm" method="POST" onsubmit="return false"
                        action="{{ route('leaves.update', $leave->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <select class="form-select @error('employee_id') is-invalid @enderror"
                                        id="leave-employee-id" name="employee_id" aria-label="Employee ID">
                                        <option value="" disabled selected>Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id', $leave->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->user->name }} <small>({{ $employee->number }})</small>
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="leave-employee-id">Employee</label>
                                    @error('employee_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <select class="form-select @error('leave_type') is-invalid @enderror" id="leave-type"
                                        name="leave_type" aria-label="Leave Type">
                                        <option value="" disabled selected>Select Leave Type</option>
                                        @foreach ($leaveTypes as $leaveType)
                                            <option value="{{ $leaveType }}"
                                                {{ old('leave_type', $leave->leave_type) == $leaveType ? 'selected' : '' }}>
                                                {{ ucfirst($leaveType) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="leave-type">Leave Type</label>
                                    @error('leave_type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="leave-start-date" placeholder="Start Date" name="start_date"
                                        aria-label="Start Date" value="{{ old('start_date', $leave->start_date) }}" />
                                    <label for="leave-start-date">Start Date</label>
                                    @error('start_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="leave-end-date" placeholder="End Date" name="end_date" aria-label="End Date"
                                        value="{{ old('end_date', $leave->end_date) }}" />
                                    <label for="leave-end-date">End Date</label>
                                    @error('end_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <textarea class="form-control @error('reason') is-invalid @enderror" id="leave-reason" placeholder="Reason"
                                        name="reason" aria-label="Reason" cols="30" rows="10">{{ old('reason', $leave->reason) }}</textarea>
                                    <label for="leave-reason">Reason</label>
                                    @error('reason')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label for="leave-document">Document</label>
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="file" class="form-control @error('document') is-invalid @enderror"
                                        id="leave-document" name="document" aria-label="Document" />
                                    @error('document')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-5">
                                    <select class="form-select @error('status') is-invalid @enderror" id="leave-status"
                                        name="status" aria-label="Status">
                                        <option value="" disabled selected>Select Status</option>
                                        @foreach ($leaveStatuses as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status', $leave->status) == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="leave-status">Status</label>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/console/leaves/validation_script.js')
@endpush
