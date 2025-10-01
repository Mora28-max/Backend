<?php

namespace App\Models\Locations;

use App\Models\Customers\Customer;
use App\Models\Locations\Colony;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $table = 'zones';
    protected $fillable = ['name', 'colony_id'];

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
