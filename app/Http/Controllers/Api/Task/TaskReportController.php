<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreTaskReport;
use App\Models\TaskReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskReportController extends Controller
{
    public function index()
    {
        try {
            $taskReports = TaskReport::all();
            return responseJson($taskReports);
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to fetch Task Reports.', 500);
        }
    }

    public function store(RequestStoreTaskReport $request)
    {
        try {
            $existingReport = TaskReport::where('task_id', $request->task_id)->first();

            if ($existingReport) {
                return responseJsonError(null, 'Task has already been reported and cannot be reported again.', 400);
            }

            $data = [
                'task_id' => $request->task_id,
                'status' => $request->status,
                'note' => $request->note,
            ];

            if ($request->hasFile('proof_assignment')) {
                $data['proof_assignment'] = handleUpload('proof_assignment', 'proof-assignments');
            }

            $taskReport = TaskReport::create($data);

            return responseJson($taskReport->load(['task:id,employee_id,type', 'task.employee:id,user_id,number', 'task.employee.user:id,name']), 'Task Report created successfully.', 201);
        } catch (\Exception $e) {
            return responseJsonError($e->getMessage(), 'Failed to submit task report.', 500);
        }
    }

    public function show(string $id)
    {
        try {
            $taskReport = TaskReport::findOrFail($id);
            return responseJson($taskReport->load(['task:id,employee_id,type', 'task.employee:id,user_id,number', 'task.employee.user:id,name']), 'Task Report retrieved successfully.', 201);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Task Report: ' . $e->getMessage());
            return responseJsonError($e->getMessage(), 'Failed to fetch Task Report.', 500);
        }
    }


    public function update(RequestStoreTaskReport $request, string $id)
    {
        try {
            $taskReport = TaskReport::findOrFail($id);

            $existingReport = TaskReport::where('task_id', $taskReport->task_id)
                ->where('id', '!=', $id)
                ->where('status', '!=', 'revision')
                ->first();

            if ($existingReport) {
                return responseJsonError(null, 'Task report cannot be updated unless the status is "revision".', 400);
            }

            // Validasi data request
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

            return responseJson($taskReport->load(['task:id,employee_id,type', 'task.employee:id,user_id,number', 'task.employee.user:id,name']), 'Task Report updated successfully.', 200);
        } catch (\Exception $e) {
            Log::error('Failed to update Task Report: ' . $e->getMessage());

            return responseJsonError($e->getMessage(), 'Failed to update Task Report.', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $taskReport = TaskReport::findOrFail($id);

            $taskReport->delete();

            return responseJson(null, 'Task Report deleted successfully.');
        } catch (\Exception $e) {
            return responseJsonError($e, 'Failed to delete Task.', 500);
        }
    }
}
