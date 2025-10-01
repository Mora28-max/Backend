<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordConfirmationHelper
{
    public static function check(string $password): void
    {
        if (!Hash::check($password, Auth::user()->password)) {
            throw new \Exception('La contraseña es incorrecta.');
        }
    }
}
