@extends('layouts.app')
@section('title', 'Update Attendance')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">
                    <span class="fw-normal">Update Attendance</span>
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

                    <form class="attendance pt-0" id="attendanceForm" method="POST"
                        action="{{ route('attendances.update', $attendance->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <select class="form-select @error('employee_id') is-invalid @enderror"
                                        id="attendance-employee-id" name="employee_id" aria-label="Employee">
                                        <option value="" disabled selected>Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->user->name }} <small>({{ $employee->number }})</small>
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="attendance-employee-id">Employee</label>
                                    @error('employee_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="attendance-date" name="date" aria-label="Date"
                                        value="{{ old('date', $attendance->date) }}" />
                                    <label for="attendance-date">Date</label>
                                    @error('date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- New Field: Select Attendance Type -->
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <select class="form-select" id="attendance-type" name="attendance_type">
                                        <option value="" disabled selected>Select Attendance Type</option>
                                        <option value="check_in" @if (old('attendance_type') == 'check_in') selected @endif>Clock In
                                        </option>
                                        <option value="check_out" @if (old('attendance_type') == 'check_out') selected @endif>Clock
                                            Out</option>
                                    </select>
                                    <label for="attendance-type">Attendance Type</label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <label class="form-label text-light fw-medium d-block mb-3" for="is_late">Is Late</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_late" id="isLateYes"
                                        value="yes" @if ($attendance->status == \App\Enums\AttendanceStatus::Late) checked @endif>
                                    <label class="form-check-label" for="isLateYes">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_late" id="isLateNo"
                                        @if ($attendance->status != \App\Enums\AttendanceStatus::Late) checked @endif value="no">
                                    <label class="form-check-label" for="isLateNo">No</label>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Fields Based on Attendance Type -->
                        <div id="check-in-fields" class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="time" class="form-control @error('check_in') is-invalid @enderror"
                                        id="attendance-check-in" name="check_in" aria-label="Clock In"
                                        value="{{ old('check_in', date('H:i', strtotime($attendance->check_in))) }}" />
                                    <label for="attendance-check-in">Clock In Time</label>
                                    @error('check_in')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="text"
                                        class="form-control @error('location_check_in') is-invalid @enderror"
                                        id="attendance-location-check-in" name="location_check_in"
                                        placeholder="Location Clock In" aria-label="Location Clock In"
                                        value="{{ old('location_check_in', $attendance->location_check_in) }}" />
                                    <label for="attendance-location-check-in">Location Clock In</label>
                                    @error('location_check_in')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="text"
                                        class="form-control @error('longlat_check_in') is-invalid @enderror"
                                        id="attendance-longlat-check-in" name="longlat_check_in"
                                        placeholder="Longitude/Latitude Clock In" aria-label="Longitude/Latitude Clock In"
                                        value="{{ old('longlat_check_in', $attendance->longlat_check_in) }}" />
                                    <label for="attendance-longlat-check-in">Longitude/Latitude Clock In</label>
                                    @error('longlat_check_in')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="file"
                                        class="form-control @error('picture_check_in') is-invalid @enderror"
                                        id="attendance-picture-check-in" name="picture_check_in"
                                        aria-label="Picture Clock In" />
                                    <label for="attendance-picture-check-in">Picture Clock In</label>
                                    @error('picture_check_in')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="check-out-fields" class="row d-none">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="time" class="form-control @error('check_out') is-invalid @enderror"
                                        id="attendance-check-out" name="check_out" aria-label="Clock Out"
                                        value="{{ old('check_out', date('H:i', strtotime($attendance->check_out))) }}" />
                                    <label for="attendance-check-out">Clock Out Time</label>
                                    @error('check_out')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="text"
                                        class="form-control @error('location_check_out') is-invalid @enderror"
                                        id="attendance-location-check-out" name="location_check_out"
                                        placeholder="Location Clock Out" aria-label="Location Clock Out"
                                        value="{{ old('location_check_out', $attendance->location_check_out) }}" />
                                    <label for="attendance-location-check-out">Location Clock Out</label>
                                    @error('location_check_out')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="text"
                                        class="form-control @error('longlat_check_out') is-invalid @enderror"
                                        id="attendance-longlat-check-out" name="longlat_check_out"
                                        placeholder="Longitude/Latitude Clock Out"
                                        aria-label="Longitude/Latitude Clock Out"
                                        value="{{ old('longlat_check_out', $attendance->longlat_check_out) }}" />
                                    <label for="attendance-longlat-check-out">Longitude/Latitude Clock Out</label>
                                    @error('longlat_check_out')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-5 mt-2">
                                    <input type="file"
                                        class="form-control @error('picture_check_out') is-invalid @enderror"
                                        id="attendance-picture-check-out" name="picture_check_out"
                                        aria-label="Picture Clock Out" />
                                    <label for="attendance-picture-check-out">Picture Clock Out</label>
                                    @error('picture_check_out')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    @vite('resources/js/console/attendances/validation_script.js')
@endpush
