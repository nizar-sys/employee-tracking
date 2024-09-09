<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $token = $request->user()->createToken('authToken')->plainTextToken;

            return responseJson(['token' => $token]);
        } catch (\Exception $e) {
            return responseJsonError($e->getMessage(), status: 401);
        }
    }
}
