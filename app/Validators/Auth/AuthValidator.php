<?php

namespace App\Validators\Auth;

use App\Models\InvitationToken;
use Illuminate\Support\Facades\Auth;

class AuthValidator
{
    public static function isValidToken(string $token): InvitationToken
    {
        $token = InvitationToken::where('token', $token)
            ->where('used', false)
            ->first();
        if (!$token)
            throw new \Exception('El token de acceso no es válido.');

        return $token;
    }

    public static function isValidEmail(string $tokenEmail, string $dataEmail): void
    {
        if ($tokenEmail !== $dataEmail)
            throw new \Exception('El correo electrónico no coincide con el de la invitación de acceso.');
    }

    public static function areValidCredentials(array $data): void
    {
        if (!Auth::attempt($data))
            throw new \Exception('El correo electrónico y/o la contraseña no son correctos.');
    }
}
