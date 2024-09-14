<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreLeave;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        try {
            $leaves = Leave::with(['employee:id,user_id,number', 'employee.user:id,name'])
                ->when($request->filled('employee_id'), function ($query) use ($request) {
                    return $query->where('employee_id', $request->employee_id);
                })
                ->when($request->filled('leave_type'), function ($query) use ($request) {
                    return $query->where('leave_type', $request->leave_type);
                })
                ->when($request->filled('status'), function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })
                ->latest()->get();

            return responseJson($leaves);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to fetch leaves.', 500);
        }
    }

    public function store(RequestStoreLeave $request)
    {
        try {
            $payloadLeave = $request->validated();

            if ($request->hasFile('document')) {
                $payloadLeave['document'] = handleUpload('document', 'leaves');
            }

            $leave = Leave::create($payloadLeave);

            return responseJson($leave->load(['employee:id,user_id,number', 'employee.user:id,name']), 'Leave created successfully.', 201);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to create leave.', 500);
        }
    }

    public function update(RequestStoreLeave $request, Leave $leave)
    {
        try {
            $payloadLeave = $request->validated();
            $payloadLeave['document'] = $leave->document;

            if ($request->hasFile('document')) {
                deleteFileIfExist($leave->document);
                $payloadLeave['document'] = handleUpload('document', 'leaves');
            }

            $leave->update($payloadLeave);

            return responseJson($leave->load(['employee:id,user_id,number', 'employee.user:id,name']), 'Leave updated successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to update leave.', 500);
        }
    }

    public function destroy(Leave $leave)
    {
        try {
            deleteFileIfExist($leave->document);
            $leave->delete();
            return responseJson(null, 'Leave deleted successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to delete leave.', 500);
        }
    }
}
