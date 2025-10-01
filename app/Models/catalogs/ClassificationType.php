<?php

namespace App\Models\Catalogs;

use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Model;

class ClassificationType extends Model
{
    //
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
