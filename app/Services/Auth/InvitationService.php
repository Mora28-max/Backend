<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\InvitationToken;
use App\Mail\InvitationTokenMail;
use Illuminate\Support\Facades\Mail;
use App\Helpers\PasswordConfirmationHelper;

class InvitationService
{
    public function sendMailInvitation(array $data)
    {
        $user_exist = User::where('email', $data['email'])->first();

        if ($user_exist)
            throw new \Exception("El usuario con el correo {$data['email']} ya existe.");

        PasswordConfirmationHelper::check($data['password']);

        $invitation = InvitationToken::create([
            'email' => $data['email'],
            'token' => Str::random(40),
            'used' => false,
        ]);

        Mail::to($invitation->email)
            ->send(new InvitationTokenMail($invitation->token, env('URL_SERVER_FRONT')));
    }
}
