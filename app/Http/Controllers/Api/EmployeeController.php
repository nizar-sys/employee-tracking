<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreEmployee;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $employees = Employee::with('designation:id,name,description,updated_at')
                ->with('user:id,name,email,updated_at')
                ->when($request->filled('designation_id'), function ($query) use ($request) {
                    return $query->where('designation_id', $request->designation_id);
                })
                ->get();

            return responseJson($employees);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to fetch employees.', 500);
        }
    }

    public function store(RequestStoreEmployee $request)
    {
        try {
            $payloadEmployee = $request->validated();
            $payloadEmployee['photo'] = handleUpload('photo', 'employees');
            $newEmployee = Employee::create($payloadEmployee)->load('designation:id,name,description,updated_at')
                ->load('user:id,name,email,updated_at');

            return responseJson($newEmployee, 'Employee created successfully.', 201);
        } catch (\Exception $e) {

            return responseJsonError($e, 'Failed to create employee.', 500);
        }
    }

    public function show(Employee $employee)
    {
        try {
            $employee->load('designation:id,name,description,updated_at')
                ->load('user:id,name,email,updated_at');

            return responseJson($employee);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to fetch employee.', 500);
        }
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
            $employee->load('designation:id,name,description,updated_at')
                ->load('user:id,name,email,updated_at');

            return responseJson($employee, 'Employee updated successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to update employee.', 500);
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            deleteFileIfExist($employee->photo);
            $employee->delete();
            return responseJson(message: 'Employee deleted successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to delete employee.', 500);
        }
    }
}
