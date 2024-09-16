<?php

namespace App\Http\Controllers\Console;

use App\DataTables\EmployeeDataTable;
use App\DataTables\Scopes\EmployeeScope;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreEmployee;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request, EmployeeDataTable $dataTable)
    {
        $designations = Designation::select('id', 'name')->get();

        return $dataTable
            ->addScope(new EmployeeScope($request))
            ->render('console.employees.index', compact('designations'));
    }

    public function create()
    {
        $designations = Designation::select('id', 'name')->get();
        $users = User::whereDoesntHave('employee')->whereHas('roles', function ($query) {
            $query->where('name', 'Employee');
        })->get();

        return view('console.employees.create', compact('designations', 'users'));
    }

    public function store(RequestStoreEmployee $request)
    {
        try {
            $payloadEmployee = $request->validated();
            $payloadEmployee['photo'] = handleUpload('photo', 'employees');
            Employee::create($payloadEmployee);
            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->withInput()->with('error', 'Failed to create employee.');
        }
    }

    public function edit(Employee $employee)
    {
        $designations = Designation::select('id', 'name')->get();
        $users = User::whereDoesntHave('employee')->whereHas('roles', function ($query) {
            $query->where('name', 'Employee');
        })->orWhereHas('employee', function ($query) use ($employee) {
            $query->where('id', $employee->id);
        })->get();

        return view('console.employees.edit', compact('employee', 'designations', 'users'));
    }

    public function update(RequestStoreEmployee $request, Employee $employee)
    {
        try {
            $payloadEmployee = $request->validated();
            $payloadEmployee['photo'] = $employee->photo;

            if ($request->hasFile('photo')) {
                deleteFileIfExist($employee->photo);
                $payloadEmployee['photo'] = handleUpload('photo', 'employees');
            }

            $employee->update($payloadEmployee);
            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update employee.');
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            deleteFileIfExist($employee->photo);
            $employee->delete();
            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete employee.');
        }
    }
}
