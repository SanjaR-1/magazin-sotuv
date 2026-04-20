<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): array{
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'message' => 'Registered successfully',
            'user' => $user,
            'token' => $token,
        ];
    }
    public function login(array $data): array{
        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email yoki parol xato']
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ];
    }
    public function logout(User $user): array{
        $user->currentAccessToken()?->delete();
        return [
            'message' => 'Logged out successfully',
        ];
    }
}