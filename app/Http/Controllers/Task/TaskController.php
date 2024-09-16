<?php

namespace App\Http\Controllers\Task;

use App\DataTables\Scopes\TaskScope;
use App\DataTables\TaskDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreTask;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(TaskDataTable $dataTable, Request $request)
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();
        return $dataTable->addScopes([
            new TaskScope($request)
        ])->render('console.tasks.index', compact('employees'));
    }

    public function create()
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();

        return view('console.tasks.create', compact('employees'));
    }

    public function store(RequestStoreTask $request)
    {
        try {
            $validatedData = $request->validated();

            $validatedData['is_validate_location'] = $validatedData['is_validate_location'] === 'yes' ? 1 : 0;

            Task::create($validatedData);

            return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the task.');
        }
    }

    public function show(Task $task)
    {
        return view('console.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $employees = Employee::select('id', 'user_id', 'number')->with('user:id,name')->get();
        return view('console.tasks.edit', compact('task', 'employees'));
    }

    public function update(RequestStoreTask $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $validatedData['is_validate_location'] = $validatedData['is_validate_location'] === 'yes' ? 1 : 0;

            $task = Task::findOrFail($id);

            $task->update($validatedData);

            return redirect()->route('tasks.index', $task->id)->with('success', 'Task updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update task. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);

            $task->delete();

            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('tasks.index')->with('error', 'Failed to delete task. Please try again.');
        }
    }
}
