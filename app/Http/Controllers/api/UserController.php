<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
class UserController extends Controller
{
    public function __construct(protected UserService $userService) {
        $this->middleware(['auth:sanctum', 'admin']);
    }
    public function index() {
        $users = $this->userService->getAllUsers();
        return UserResource::collection($users);
    }
    public function store(StoreUserRequest $request) {
        $user = $this->userService->createUser($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Foydalanuvchi va uning profili yaratildi',
            'data'    => new UserResource($user)
        ], 201);
    }
}