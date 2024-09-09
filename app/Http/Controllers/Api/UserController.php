<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreOrUpdateUser;
use App\Http\Requests\RequestStoreUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::select('id', 'name', 'email', 'avatar')->latest()->get();

            return responseJson($users);
        } catch (\Exception $e) {
            return responseJsonError($e->getMessage(), status: 500);
        }
    }

    public function store(RequestStoreUser $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create($request->validated());
            $user->assignRole(Role::find($request->role_id));

            DB::commit();
            return responseJson($user, status: 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return responseJsonError('Failed to create user.', status: 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->role_id = $user->roles?->first()?->id ?? null;

            return responseJson($user);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return responseJsonError('User not found.', status: 404);
        }
    }

    public function update(RequestStoreUser $request, $id)
    {
        DB::beginTransaction();
        try {
            $payloadUpdateUser = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->password) {
                $payloadUpdateUser['password'] = bcrypt($request->password);
            }

            $user = User::findOrFail($id);
            $user->update($payloadUpdateUser);
            $user->syncRoles(Role::find($request->role_id));

            DB::commit();
            return responseJson($user);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return responseJsonError('Failed to update user.', status: 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            User::findOrFail($id)->delete();

            DB::commit();
            return responseJson(null, 'User deleted!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return responseJsonError('Failed to delete user.', status: 500);
        }
    }
}
