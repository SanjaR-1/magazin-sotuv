<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserService
{
    public function getAllUsers()
    {
        return User::latest()->paginate(10);
    }
    public function createUser(array $data) {
        return \DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => \Hash::make($data['password']),
                'role'     => $data['role'],
            ]);
            if ($user->role === 'customer') {
                $user->customer()->create([
                    'first_name'   => $data['first_name'] ?? $user->name,
                    'last_name'    => $data['last_name'] ?? '',
                    'phone_number' => $data['phone_number'],
                ]);
            } elseif ($user->role === 'driver') {
                $user->driver()->create([
                    'first_name'   => $data['first_name'] ?? $user->name,
                    'last_name'    => $data['last_name'] ?? '',
                    'phone_number' => $data['phone_number'],
                    'is_active'    => true,
                ]);
            }
            return $user;
        });
    }
    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}