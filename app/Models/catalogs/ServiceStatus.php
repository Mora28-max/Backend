<?php

namespace App\Models\Catalogs;

use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Model;

class ServiceStatus extends Model
{
    //
    protected $table = 'service_status';

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }
}
