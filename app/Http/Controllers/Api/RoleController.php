<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        try {
            $roles = Role::select('id', 'name')->withCount('users')->get();

            return responseJson([
                'roles' => $roles,
            ]);
        } catch (\Exception $e) {
            return responseJsonError($e);
        }
    }

    public function store(RequestStoreRole $request)
    {
        try {
            DB::beginTransaction();

            $role = Role::create(['name' => $request->name]);

            DB::commit();

            return responseJson([
                'role' => $role,
            ], 'Role created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return responseJsonError($e, 'Failed to create role', 500);
        }
    }

    public function update(RequestStoreRole $request, Role $role)
    {
        try {
            DB::beginTransaction();

            $role->update(['name' => $request->name]);

            DB::commit();

            return responseJson([
                'role' => $role,
            ], 'Role updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return responseJsonError($e, 'Failed to update role', 500);
        }
    }

    public function destroy($roleId)
    {
        DB::beginTransaction();

        try {
            $role = Role::find($roleId);

            if (!$role) {
                return responseJsonError(null, 'Role not found', 404);
            }

            $role->delete();

            DB::commit();

            return responseJson(null, 'Role deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return responseJsonError($e, 'Failed to delete role', 500);
        }
    }
}
