<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;


use App\Http\Actions\LoginAction;
use App\Http\Actions\RegisterAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController
{
    public function register(Request $request, RegisterAction $action): JsonResponse
    {
        return response()->json($action->execute($request->all()));
    }

    public function login(Request $request, LoginAction $action): JsonResponse
    {
        return response()->json($action->execute($request->all()));
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Tokens Revoked']);
    }
}
