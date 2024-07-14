<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Gate::authorize('viewAny');

            $users = User::select([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ])->get()->toArray();

        } catch (AuthorizationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'This action is unauthorized.',
                'data' => [],
            ], ResponseCode::HTTP_FORBIDDEN);
        } catch (\Throwable $e) {
            Log::error('Failed to retrieve users: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage(),
                'data' => [],
            ], ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users,
        ]);
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
    public function show(Request $request, string $id)
    {
        try {
            $user = User::select([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ])->find($id);

            Gate::authorize('view', $user);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                    'data' => [],
                ], ResponseCode::HTTP_NOT_FOUND);
            }

        } catch (AuthorizationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'This action is unauthorized.',
                'data' => [],
            ], ResponseCode::HTTP_FORBIDDEN);
        } catch (\Throwable $e) {
            Log::error('Failed to retrieve user: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve user: ' . $e->getMessage(),
                'data' => [],
            ], ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'User retrieved successfully',
            'data' => $user,
        ]);
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
