<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreDesignation;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        try {
            $designations = Designation::select('id', 'name', 'description')->latest()->get();
            return responseJson($designations);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to fetch designations.', 500);
        }
    }

    public function store(RequestStoreDesignation $request)
    {
        try {
            $designation = Designation::create($request->validated());
            return responseJson($designation, 'Designation created successfully.', 201);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to create designation.', 500);
        }
    }

    public function update(RequestStoreDesignation $request, Designation $designation)
    {
        try {
            $designation->update($request->validated());
            return responseJson($designation, 'Designation updated successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to update designation.', 500);
        }
    }

    public function destroy(Designation $designation)
    {
        try {
            $designation->delete();
            return responseJson(null, 'Designation deleted successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to delete designation.', 500);
        }
    }
}
