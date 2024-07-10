<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::select([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ])->get()->toArray();

            return response()->json([
                'status' => true,
                'message' => 'Users retrieved successfully',
                'data' => $users,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to retrieve users: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve users',
                'data' => [],
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Created Successfully',
                'data' => [
                    'token' => $user->createToken('auth_token')->plainTextToken
                ],
            ], ResponseCode::HTTP_CREATED);
        } catch (\Throwable $e) {
            Log::error('Failed to create user: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create user',
                'data' => [],
            ], ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
