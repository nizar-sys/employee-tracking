<?php

namespace App\Http\Controllers\Console;

use App\DataTables\DesignationDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreDesignation;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(DesignationDataTable $dataTable)
    {
        return $dataTable->render('console.designations.index');
    }

    public function create()
    {
        return view('console.designations.create');
    }

    public function store(RequestStoreDesignation $request)
    {
        try {
            Designation::create($request->validated());
            return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create designation.');
        }
    }

    public function edit(Designation $designation)
    {
        return view('console.designations.edit', compact('designation'));
    }

    public function update(RequestStoreDesignation $request, Designation $designation)
    {
        try {
            $designation->update($request->validated());
            return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update designation.');
        }
    }

    public function destroy(Designation $designation)
    {
        try {
            $designation->delete();
            return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete designation.');
        }
    }
}
