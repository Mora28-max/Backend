<?php

namespace App\Services\AuthCustomer;

use App\Helpers\FormatStrings;
use App\Models\Customers\Customer;
use Illuminate\Support\Facades\Auth;

class AuthCustomerService
{
    public function login(array $credentials): array
    {
        $customer = Customer::find($credentials['customer_id']);
        if (!$customer->phone) throw new \Exception('Usuario no encontrado, por favor activa tu cuenta');
        if ($credentials['phone'] !== $customer->phone) throw new \Exception('Usuario no encontrado');

        $token = $customer->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'id' => $customer->id,
            'name' => $customer->fullName
        ];
    }

    public function register(array $data): void
    {
        $customer = Customer::findOrFail($data['customer_id']);
        if (!empty($customer->phone))
            throw new \Exception("Usted ya ha dado de alta su cuenta. Inicie Sesión con su teléfono registrado.");

        $is_first_name_equal = FormatStrings::normalizeString($customer->first_name) === FormatStrings::normalizeString($data['first_name']);
        $is_last_name_equal = FormatStrings::normalizeString($customer->last_name) === FormatStrings::normalizeString($data['last_name']);

        if (!$is_first_name_equal || !$is_last_name_equal)
            throw new \Exception('Los nombres no coinciden con los registrados en su cuenta');

        $phone_format = preg_match('/^[0-9]{10}$/', $data['phone']);
        if (!$phone_format) throw new \Exception('El formato del teléfono es inválido');

        $customer->phone = $data['phone'];
        $customer->save();
    }

    public function logout(): void
    {
        Auth::guard('customer')->user()->currentAccessToken()->delete();
    }
}
