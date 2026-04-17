<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function paginate(int $perPage = 10, ?string $role = null): LengthAwarePaginator
    {
        return User::query()
            ->when($role, function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate($perPage);
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function updateRole(User $user, array $data): User
    {
        if ($user->role === 'admin' && $data['role'] !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();

            if ($adminCount <= 1) {
                throw ValidationException::withMessages([
                    'role' => ['Sistemada kamida bitta admin qolishi kerak'],
                ]);
            }
        }

        $user->update([
            'role' => $data['role'],
        ]);

        return $user->fresh();
    }

    public function delete(User $user): array
    {
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();

            if ($adminCount <= 1) {
                throw ValidationException::withMessages([
                    'user' => ['Oxirgi adminni o‘chirib bo‘lmaydi'],
                ]);
            }
        }

        $user->delete();

        return [
            'message' => 'User deleted successfully',
        ];
    }
}