<?php

namespace App\Http\Controllers\CustomerView;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerView\LoginRequest;
use App\Http\Requests\CustomerView\RegisterRequest;
use App\Services\AuthCustomer\AuthCustomerService;

class AuthCustomerController extends Controller
{
    public function __construct(protected AuthCustomerService $auth_customer_service) {}

    public function customerRegister(RegisterRequest $request)
    {
        try {
            $this->auth_customer_service->register($request->validated());
            return response([
                'message' => 'Registro exitoso',
            ], 200);
        } catch (\Exception $th) {
            return response([
                'message' => 'Error al registrar el cliente',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function customerLogin(LoginRequest $request)
    {
        try {
            $data  = $this->auth_customer_service->login($request->validated());
            return response([
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function customerLogout()
    {
        try {
            $this->auth_customer_service->logout();
            return response([
                'message' => 'Logout successful'
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al cerrar sesión',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function verifyToken(Request $request)
    {
        $customer = $request->user('customer');
        if (!$customer) return response(['error' => 'Token inválido'], 401);
        return response([
            'data' => [
                'id' => $customer->id,
                'name' => $customer->fullName,
                'service' => $customer->serviceType->name
            ]
        ]);
    }
}
