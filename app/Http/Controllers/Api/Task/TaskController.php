<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreTask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $tasks = Task::all();
            return responseJson($tasks);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to fetch Tasks.', 500);
        }
    }

    public function store(RequestStoreTask $request)
    {
        try {
            $validatedData = $request->validated();

            $validatedData['is_validate_location'] = $validatedData['is_validate_location'] === 'yes' ? 1 : 0;

            $task = Task::create($validatedData);

            return responseJson($task->load(['employee:id,user_id,number', 'employee.user:id,name']), 'Task created successfully.', 201);
        } catch (\Exception $e) {
            return responseJsonError($e, 'An error occurred while creating the task.', 500);
        }
    }

    public function show(Task $task)
    {
        try {
            return responseJson($task->load(['employee:id,user_id,number', 'employee.user:id,name']), 'Task retrieved successfully.', 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Task: ' . $e->getMessage());

            return responseJsonError($e->getMessage(), 'Failed to fetch Task.', 500);
        }
    }


    public function update(RequestStoreTask $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $validatedData['is_validate_location'] = $validatedData['is_validate_location'] === 'yes' ? 1 : 0;

            $task = Task::findOrFail($id);

            $task->update($validatedData);

            return responseJson($task, 'Task updated successfully!', 200);
        } catch (\Exception $e) {
            return responseJsonError(null, 'Failed to update task. Please try again.', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);

            $task->delete();

            return responseJson(null, 'Task deleted successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to delete Task.', 500);
        }
    }
}
