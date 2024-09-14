<?php

namespace App\Http\Controllers\Hr;

use App\DataTables\AttendanceDataTable;
use App\DataTables\Scopes\AttendanceScope;
use App\Enums\AttendanceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreAttendance;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index(Request $request, AttendanceDataTable $dataTable)
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();
        $attendanceStatuses = AttendanceStatus::asArray();

        return $dataTable
        ->addScope(new AttendanceScope($request))
        ->render('console.attendances.index', compact('employees', 'attendanceStatuses'));
    }

    public function create()
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();

        return view('console.attendances.create', compact('employees'));
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
                return back()->withInput()->with('error', 'Employee has checked in already.');
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
                return back()->withInput()->with('error', 'Employee has not checked in yet.');
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
                Attendance::create($payloadAttendance);
            }
            return redirect()->route('attendances.index')->with('success', 'Attendance created successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Failed to create attendance.');
        }
    }


    public function edit(Attendance $attendance)
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();

        return view('console.attendances.edit', compact('attendance', 'employees'));
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
            $attendance->update($payloadAttendance);
            return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update attendance.');
        }
    }


    public function destroy(Attendance $attendance)
    {
        try {
            deleteFileIfExist($attendance->picture_check_in);
            deleteFileIfExist($attendance->picture_check_out);
            $attendance->delete();
            return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete attendance.');
        }
    }
}
