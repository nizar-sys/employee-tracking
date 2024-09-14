<?php

namespace App\Http\Controllers\Hr;

use App\DataTables\LeaveDataTable;
use App\DataTables\Scopes\LeaveScope;
use App\Enums\LeaveStatus;
use App\Enums\LeaveType;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreLeave;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request, LeaveDataTable $dataTable)
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();
        $leaveTypes = LeaveType::asArray();
        $leaveStatuses = LeaveStatus::asArray();

        return $dataTable
        ->addScope(new LeaveScope($request))
        ->render('console.leaves.index', compact('employees', 'leaveTypes', 'leaveStatuses'));
    }

    public function create()
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();
        $leaveTypes = LeaveType::asArray();
        $leaveStatuses = LeaveStatus::asArray();

        return view('console.leaves.create', compact('employees', 'leaveTypes', 'leaveStatuses'));
    }

    public function store(RequestStoreLeave $request)
    {
        try {
            $payloadLeave = $request->validated();

            if ($request->hasFile('document')) {
                $payloadLeave['document'] = handleUpload('document', 'leaves');
            }

            Leave::create($payloadLeave);

            return redirect()->route('leaves.index')->with('success', 'Leave created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create leave.');
        }
    }

    public function edit(Leave $leave)
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();
        $leaveTypes = LeaveType::asArray();
        $leaveStatuses = LeaveStatus::asArray();

        return view('console.leaves.edit', compact('leave', 'employees', 'leaveTypes', 'leaveStatuses'));
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

            return redirect()->route('leaves.index')->with('success', 'Leave updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update leave.');
        }
    }

    public function destroy(Leave $leave)
    {
        try {
            deleteFileIfExist($leave->document);
            $leave->delete();
            return redirect()->route('leaves.index')->with('success', 'Leave deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete leave.');
        }
    }
}
