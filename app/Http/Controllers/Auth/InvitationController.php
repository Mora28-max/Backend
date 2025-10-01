<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\InvitationService;
use App\Http\Requests\Auth\InvitationRequest;

class InvitationController extends Controller
{

    public function __construct(protected InvitationService $invitationService) {}

    public function sendInvitation(InvitationRequest $request)
    {
        try {
            $this->invitationService->sendMailInvitation($request->validated());
            return response([
                'message' => "Token enviado correctamente al email: {$request['email']}.",
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al enviar el token de invitación.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
