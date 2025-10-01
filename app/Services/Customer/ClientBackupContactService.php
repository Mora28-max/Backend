<?php

namespace App\Services\Customer;

use App\Models\Customers\ClientBackupContact;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientBackupContactService
{
    public function getAllByCustomerId(int $customer_id): LengthAwarePaginator
    {
        return ClientBackupContact::where('customer_id', $customer_id)->paginate(15);
    }

    public function create(array $data): ClientBackupContact
    {
        return ClientBackupContact::create($data);
    }

    public function update(ClientBackupContact $beneficiary, array $data): ClientBackupContact
    {
        $beneficiary->update($data);
        return $beneficiary;
    }

    public function delete(ClientBackupContact $beneficiary): void
    {
        $beneficiary->delete();
    }
}
