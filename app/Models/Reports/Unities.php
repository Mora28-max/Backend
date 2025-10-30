<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;

class Unities extends Model
{
    //
    protected $table = 'unities';

      protected $fillable = ['name', 'abbreviation'];

    // Relación con AddMaterial
    public function materials()
    {
        return $this->hasMany(\App\Models\Inventory\InventoryMaterial::class, 'unit_type_id', 'id');
    }
}
