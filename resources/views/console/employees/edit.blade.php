@extends('layouts.app')
@section('title', 'Update Employee')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">
                    <span class="fw-normal">Update Employee</span>
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

                    <form class="employee pt-0" id="employeeForm" method="POST" action="{{ route('employees.update', $employee->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Number -->
                        <div class="row mt-3">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-floating form-floating-outline mb-3">
                                    <input type="text" class="form-control @error('number') is-invalid @enderror"
                                        id="employee-number" placeholder="Employee Number" name="number"
                                        value="{{ old('number', $employee->number) }}">
                                    <label for="employee-number">Employee Number</label>
                                    @error('number')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- User -->
                            <div class="col-sm-12 col-md-4">
                                <div class="form-floating form-floating-outline mb-3">
                                    <select class="form-control @error('user_id') is-invalid @enderror" id="employee-user"
                                        name="user_id">
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="employee-user">User Detail</label>
                                    @error('user_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Designation -->
                            <div class="col-sm-12 col-md-4">
                                <div class="form-floating form-floating-outline mb-3">
                                    <select class="form-control @error('designation_id') is-invalid @enderror"
                                        id="employee-designation" name="designation_id">
                                        <option value="">Select Designation</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}"
                                                {{ old('designation_id', $employee->designation_id) == $designation->id ? 'selected' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="employee-designation">Designation</label>
                                    @error('designation_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Phone and Address -->
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-3">
                                    <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                        id="employee-phone" placeholder="Phone Number" name="phone"
                                        value="{{ old('phone', $employee->phone) }}">
                                    <label for="employee-phone">Phone Number</label>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-3">
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="employee-address" placeholder="Address"
                                        name="address">{{ old('address', $employee->address) }}</textarea>
                                    <label for="employee-address">Address</label>
                                    @error('address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Zip Code and Date of Birth -->
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-3">
                                    <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                        id="employee-zip-code" placeholder="Zip Code" name="zip_code"
                                        value="{{ old('zip_code', $employee->zip_code) }}">
                                    <label for="employee-zip-code">Zip Code</label>
                                    @error('zip_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-3">
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                        id="employee-dob" placeholder="Date of Birth" name="date_of_birth"
                                        value="{{ old('date_of_birth', $employee->date_of_birth) }}">
                                    <label for="employee-dob">Date of Birth</label>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Photo Upload with Preview -->
                        <div class="row">
                            <div class="col-sm-12 col-md-6 dropzone-upload">
                                <label for="photo">Profile Picture</label>
                                <div class="form-floating form-floating-outline mb-5 mt-2 dropzone-box">
                                    <div id="dropzone-preview" class="mb-3">
                                    </div>
                                    <div class="dz-message needsclick">
                                        Drop files here or click here to upload
                                    </div>
                                    <input type="file" name="photo" id="photo" class="d-none" />
                                    @error('photo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Work Hour -->
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-3">
                                    <input type="text" class="form-control @error('work_hour') is-invalid @enderror"
                                        id="employee-work-hour" placeholder="Work Hour" name="work_hour"
                                        value="{{ old('work_hour', $employee->work_hour) }}">
                                    <label for="employee-work-hour">Work Hour</label>
                                    @error('work_hour')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit and Cancel Buttons -->
                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/console/employees/employee_validation_script.js')
    <script>
        var existingFiles = @json($employee->photo);
    </script>
@endpush
