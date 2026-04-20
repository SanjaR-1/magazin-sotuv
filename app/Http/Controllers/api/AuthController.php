<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}
    public function register(RegisterRequest $request): AuthResource{
        $result = $this->authService->register($request->validated());

        return (new AuthResource($result))
            ->additional([
                'success' => true,
            ]);
    }
    public function login(LoginRequest $request): AuthResource{
        $result = $this->authService->login($request->validated());
        return (new AuthResource($result))
            ->additional([
                'success' => true,
            ]);
    }
    public function logout(): JsonResponse{
        $result = $this->authService->logout(request()->user());
        return response()->json([
            'success' => true,
            'message' => $result['message'],
        ]);
    }
}