<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Controllers\Controller;
class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}
    public function index(Request $request){
        $perPage = (int) $request->get('per_page', 10);
        $role = $request->get('role');
        $users = $this->userService->paginate($perPage, $role);
        return UserResource::collection($users);
    }
    public function show(User $user): UserResource{
        $user = $this->userService->show($user);
        return new UserResource($user);
    }
    public function updateRole(UpdateUserRoleRequest $request, User $user): JsonResponse{
        $user = $this->userService->updateRole($user, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully',
            'data' => new UserResource($user),
        ]);
    }
    public function destroy(User $user): JsonResponse{
        $result = $this->userService->delete($user);
        return response()->json([
            'success' => true,
            'message' => $result['message'],
        ]);
    }
}