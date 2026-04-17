<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tizimga kirish va Token olish
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Parolni tekshirish
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kiritilgan ma\'lumotlar xato.'],
            ]);
        }

        // Eski tokenlarni o'chirish (ixtiyoriy, xavfsizlik uchun)
        $user->tokens()->delete();

        // Yangi token yaratish
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Tizimdan chiqish (Tokenni bekor qilish)
     */
    public function logout(Request $request)
    {
        // Joriy tokenni o'chirish
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tizimdan muvaffaqiyatli chiqildi.'
        ]);
    }
}