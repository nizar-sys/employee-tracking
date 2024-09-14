<?php

namespace App\Http\Controllers\Api;

use App\Enums\AttendanceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreAttendance;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        try {
            $attendances = Attendance::with(['employee:id,user_id,number', 'employee.user:id,name'])->latest()->get();
            return responseJson($attendances);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to fetch attendances.', 500);
        }
    }

    public function store(RequestStoreAttendance $request)
    {
        $validatedData = $request->validated();

        $payloadAttendance = [
            'employee_id' => $validatedData['employee_id'],
            'date' => $validatedData['date'],
            'time' => '08:00',
            'status' => AttendanceStatus::Present,
        ];

        if (isset($validatedData['is_late'])) {
            $payloadAttendance['status'] = $validatedData['is_late'] === 'yes' ? AttendanceStatus::Late : $payloadAttendance['status'];
        } elseif ($validatedData['attendance_type'] === 'check_in') {
            $checkInTime = strtotime($validatedData['check_in']);
            $eightAm = strtotime($payloadAttendance['time']);
            if ($checkInTime > $eightAm) {
                $payloadAttendance['status'] = AttendanceStatus::Late;
            }
        }

        $existingAttendance = Attendance::where('employee_id', $validatedData['employee_id'])
            ->where('date', $validatedData['date'])
            ->where('time', $payloadAttendance['time'])
            ->whereNotNull('check_in')
            ->first();

        if ($validatedData['attendance_type'] === 'check_in') {
            if ($existingAttendance) {
                return responseJsonError(null, 'Employee has checked in already.', 500);
            }

            $payloadAttendance = array_merge($payloadAttendance, [
                'check_in' => $validatedData['check_in'],
                'location_check_in' => $validatedData['location_check_in'],
                'longlat_check_in' => $validatedData['longlat_check_in'],
                'is_valid_location_check_in' => true,
            ]);

            if ($request->hasFile('picture_check_in')) {
                $payloadAttendance['picture_check_in'] = handleUpload('picture_check_in', 'attendance_pictures');
            }
        } elseif ($validatedData['attendance_type'] === 'check_out') {

            $checkedOut = Attendance::where('employee_id', $validatedData['employee_id'])
                ->where('date', $validatedData['date'])
                ->where('time', $payloadAttendance['time'])
                ->whereNotNull('check_out')
                ->first();

            if ($checkedOut) {
                return responseJsonError(null, 'Employee has checked out already.', 500);
            }

            if (!$existingAttendance) {
                return responseJsonError(null, 'Employee has not checked in yet.', 500);
            }

            $payloadAttendance = array_merge($payloadAttendance, [
                'check_out' => $validatedData['check_out'],
                'location_check_out' => $validatedData['location_check_out'],
                'longlat_check_out' => $validatedData['longlat_check_out'],
                'is_valid_location_check_out' => true,
            ]);

            if ($request->hasFile('picture_check_out')) {
                $payloadAttendance['picture_check_out'] = handleUpload('picture_check_out', 'attendance_pictures');
            }
        }

        try {
            if ($existingAttendance) {
                unset($payloadAttendance['status']);
                $existingAttendance->update($payloadAttendance);
            } else {
                $existingAttendance = Attendance::create($payloadAttendance);
            }
            return responseJson($existingAttendance->load(['employee:id,user_id,number', 'employee.user:id,name']), 'Attendance created successfully.', 201);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to create attendance.', 500);
        }
    }

    public function update(RequestStoreAttendance $request, Attendance $attendance)
    {
        $validatedData = $request->validated();

        $payloadAttendance = [
            'employee_id' => $validatedData['employee_id'],
            'date' => $validatedData['date'],
            'time' => '08:00',
            'status' => AttendanceStatus::Present,
        ];

        if (isset($validatedData['is_late'])) {
            $payloadAttendance['status'] = $validatedData['is_late'] === 'yes' ? AttendanceStatus::Late : AttendanceStatus::Present;
        } elseif ($validatedData['attendance_type'] === 'check_in') {
            $isLate = strtotime($validatedData['check_in']) > strtotime($payloadAttendance['time']);
            if ($isLate) {
                $payloadAttendance['status'] = AttendanceStatus::Late;
            }
        }

        $existingAttendance = Attendance::where('employee_id', $validatedData['employee_id'])
            ->where('date', $validatedData['date'])
            ->where('time', $payloadAttendance['time'])
            ->whereNotNull('check_in')
            ->first();

        if ($validatedData['attendance_type'] === 'check_in') {
            if (!$existingAttendance) {
                return back()->withInput()->with('error', 'Employee has not checked in already.');
            }

            $payloadAttendance = array_merge($payloadAttendance, [
                'check_in' => $validatedData['check_in'],
                'location_check_in' => $validatedData['location_check_in'],
                'longlat_check_in' => $validatedData['longlat_check_in'],
                'is_valid_location_check_in' => true,
            ]);

            if ($request->hasFile('picture_check_in')) {
                deleteFileIfExist($attendance->picture_check_in); // Hapus gambar sebelumnya
                $payloadAttendance['picture_check_in'] = handleUpload('picture_check_in', 'attendance_pictures');
            }
        } elseif ($validatedData['attendance_type'] === 'check_out') {
            if (!$existingAttendance) {
                return back()->withInput()->with('error', 'Employee has not checked in yet.');
            }

            $payloadAttendance = array_merge($payloadAttendance, [
                'check_out' => $validatedData['check_out'],
                'location_check_out' => $validatedData['location_check_out'],
                'longlat_check_out' => $validatedData['longlat_check_out'],
                'is_valid_location_check_out' => true,
            ]);

            if ($request->hasFile('picture_check_out')) {
                deleteFileIfExist($attendance->picture_check_out); // Hapus gambar sebelumnya
                $payloadAttendance['picture_check_out'] = handleUpload('picture_check_out', 'attendance_pictures');
            }
        }

        try {
            $attendance->update($request->validated());
            return responseJson($attendance->load(['employee:id,user_id,number', 'employee.user:id,name']), 'Attendance updated successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to update attendance.', 500);
        }
    }

    public function destroy(Attendance $attendance)
    {
        try {
            $attendance->delete();
            return responseJson(null, 'Attendance deleted successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to delete attendance.', 500);
        }
    }
}
