<?php

namespace App\Http\Controllers\Auth;

use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\RegisterResource;

class AuthController extends Controller
{
    public function __construct(protected AuthService $auth_service) {}

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->auth_service->registerUser($request->validated());
            $user->token = $user->createToken('token')->plainTextToken;
            return response([
                'message' => 'Registro exitoso.',
                'data' => new RegisterResource($user),
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Hubo un error con el registro',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->auth_service->loginUser($request->validated());
            $token = $user->createToken('token')->plainTextToken;
            $user->token = $token;
            return response([
                'data' => new LoginResource($user)
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al iniciar sesión.',
                'error' => $th->getMessage(),
            ], 401);
        }
    }

    public function logout()
    {
        try {
            $this->auth_service->logoutUser();
            return response(['message' => 'Sesión cerrada.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al cerrar sesión.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function forgetPassword()
    {
        try {
            // $this->auth_service->sendResetPasswordLink();
            return response(['message' => 'Se ha enviado un enlace para restablecer la contraseña a su correo electrónico.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al enviar el enlace para restablecer la contraseña.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
