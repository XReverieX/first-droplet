<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    public function login(LoginAuthRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $auth = Auth::attempt($credentials, $request->get('rememberMe', false));

            if (!$auth) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'The provided credentials are incorrect.',
                        'data' => []
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Authentication successful.',
                    'data' => [
                        'token' => Auth::user()->createToken('auth_token')->plainTextToken,
                    ]
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Failed to login user: ' . $e->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Failed to login.',
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'logout successful.',
                    'data' => []
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Failed to logout: ' . $e->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Failed to logout.',
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
