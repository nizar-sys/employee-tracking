<?php

namespace App\Http\Controllers\Task;

use App\DataTables\Scopes\TaskReportScope;
use App\DataTables\TaskReportDataTable;
use App\Enums\TaskReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreTaskReport;
use App\Models\Employee;
use App\Models\Task;
use App\Models\TaskReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaskReportDataTable $dataTable, Request $request)
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();
        $statuses = TaskReportStatus::getValues();
        return $dataTable->addScopes([
            new TaskReportScope($request)
        ])->render('console.task-reports.index', compact('employees', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();
        $tasks = Task::with('employee')->whereDoesntHave('taskReport')->get();
        $statuses = TaskReportStatus::getValues();

        return view('console.task-reports.create', compact('employees', 'tasks', 'statuses'));
    }

    public function getEmployeesByTask(Task $task)
    {
        $employee = $task->employee;

        if ($employee) {
            return response()->json([
                'name' => $employee->user->name // Adjust as needed
            ]);
        }

        return response()->json(['name' => ''], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreTaskReport $request)
    {
        try {
            $data = [
                'task_id' => $request->task_id,
                'status' => $request->status,
                'note' => $request->note,
            ];

            if ($request->hasFile('proof_assignment')) {
                $data['proof_assignment'] = handleUpload('proof_assignment', 'proof-assignments');
            }

            TaskReport::create($data);

            return redirect(route('task-reports.index'))->with('success', 'Task report submitted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to submit task report.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $taskReport = TaskReport::findOrFail($id);
        return view('console.task-reports.show', compact('taskReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $taskReport = TaskReport::findOrFail($id);
        $task = Task::findOrFail($taskReport->task_id);
        $statuses = TaskReportStatus::getValues();
        return view('console.task-reports.edit', compact('taskReport', 'task', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestStoreTaskReport $request, string $id)
    {
        try {
            $taskReport = TaskReport::findOrFail($id);

            $validatedData = $request->validated();

            if ($request->hasFile('proof_assignment')) {
                if ($taskReport->proof_assignment) {
                    deleteFileIfExist($taskReport->proof_assignment);
                }

                $validatedData['proof_assignment'] = handleUpload('proof_assignment', 'proof-assignments');
            } else {
                $validatedData['proof_assignment'] = $taskReport->proof_assignment;
            }

            $taskReport->update($validatedData);

            return redirect()->route('task-reports.index')->with('success', 'Task Report updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update Task Report: ' . $e->getMessage());
            return redirect()->route('task-reports.index')->with('error', 'Failed to update Task Report.');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $taskReport = TaskReport::findOrFail($id);
            deleteFileIfExist($taskReport->proof_assignment);
            $taskReport->delete();
            return redirect()->route('task-reports.index')->with('success', 'Task Report deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('task-reports.index')->with('error', 'Failed to delete Task Report: ' . $e->getMessage());
        }
    }
}
