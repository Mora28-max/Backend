<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonType extends Model
{
     use HasFactory;

    protected $table = 'person_types';

    protected $fillable = ['name'];

    // Relación con proveedores
    public function providers()
    {
        // La FK en providers debe llamarse person_type_id
        return $this->hasMany(Provider::class, 'person_type_id', 'id');
    
    }
}
