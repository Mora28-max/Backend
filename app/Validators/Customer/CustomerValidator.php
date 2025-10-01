<?php

namespace App\Validators\Customer;

use App\Models\Catalogs\UseOfType;
use App\Models\Customers\Customer;
use App\Models\Catalogs\CustomerType;
use App\Models\Catalogs\ServiceStatus;
use App\Models\Catalogs\ClassificationUse;
use App\Models\Catalogs\ClassificationType;

class CustomerValidator
{
    public static function validateClassificationAndUsage(array $data, Customer $customer): void
    {
        $classification_type_id = $data['classification_type_id'] ?? $customer->classification_type_id;
        $use_of_type_id = $data['use_of_type_id'] ?? $customer->use_of_type_id;

        $rate_exists = ClassificationUse::where('use_of_type_id', $use_of_type_id)
            ->where('classification_type_id', $classification_type_id)
            ->exists() ?? false;
        $use_of_type = UseOfType::find($use_of_type_id)->name;

        if (!$rate_exists) {
            $classification_type = ClassificationType::find($classification_type_id)->name;
            throw new \Exception("El servicio $use_of_type - $classification_type no existe");
        };

        $customer_type_id = $data['customer_type_id'] ?? $customer->customer_type_id;
        $customer_type = CustomerType::find($customer_type_id)->name;

        $exist_desc = (string) $customer_type !== 'Normal' && (string) $use_of_type !== 'Habitacional';
        if ($exist_desc)
            throw new \Exception('El tipo de usuario debe ser habitacional para ser INAPAM o Jubilado');
    }

    public static function ensureNotCancelled(Customer $customer): void
    {
        $service_status = ServiceStatus::findOrFail($customer->service_status_id);
        if ($service_status->name === 'Cancelado')
            throw new \Exception('No se puede editar un usuario cancelado.');
    }
    public static function ensureNotAlreadyCancelled(Customer $customer): void
    {
        $service_status = ServiceStatus::findOrFail($customer->service_status_id);
        if ($service_status->name === "Cancelado")
            throw new \Exception("El usuario ya ha sido cancelado.");
    }
}
