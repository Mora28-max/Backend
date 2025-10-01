<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Validators\Auth\AuthValidator;

class AuthService
{
    public function registerUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $tokenData = AuthValidator::isValidToken($data['access_token']);
            AuthValidator::isValidEmail($tokenData->email, $data['email']);
            $user = $this->createUser($data);
            $tokenData->used = true;
            $tokenData->save();
            return $user;
        });
    }

    public function loginUser(array $data): User
    {
        AuthValidator::areValidCredentials($data);
        return Auth::user()->load('roles');
    }

    public function logoutUser(): void
    {
        Auth::user()->currentAccessToken()->delete();
    }

    public function sendResetPasswordLink(string $email): void
    {
        AuthValidator::isExistingEmail($email);


    }

    private function createUser(array $data): User
    {
        return User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

}
