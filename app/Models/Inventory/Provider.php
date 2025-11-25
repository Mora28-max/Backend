<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\InventoryMaterial;
use App\Models\Inventory\Goods;
use App\Models\User;


class Provider extends Model
{
     use HasFactory;

    protected $table = 'providers';

    protected $fillable = [
        'name',
        'rfc',
        'address',
        'phone',
        'email',
        'id_user',
        'status_id',
        'person_type_id',
        'url_evidence',
        'id_type',
        'code_provider', // ✅ código secuencial
        'public_id_evidence',
    ];

    // 👇 Esto le dice a Laravel que el campo id_type es JSON
    protected $casts = [
        'id_type' => 'integer',
    ];

    /**
     * Relación con los materiales de inventario
     */
    public function inventoryMaterials()
    {
        return $this->hasMany(InventoryMaterial::class, 'provider_id', 'id');
    }

    /**
     * Relación con los bienes (goods)
     */
    public function goods()
    {
        return $this->hasMany(Goods::class, 'id_provider', 'id');
    }

    /**
     * Relación con el usuario que registró el proveedor
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * Relación con tipo de persona
     */
    public function personType()
    {
        return $this->belongsTo(PersonType::class, 'person_type_id', 'id');
    }

    /**
     * Relación con el estado del proveedor
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    /**
     * Genera automáticamente el código secuencial (1, 2, 3...)
     * y asigna id_user si no existe
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($provider) {
            // Código secuencial
            if (!$provider->code_provider) {
                $last = self::latest('id')->first();
                $provider->code_provider = $last ? $last->id + 1 : 1;
            }

            // Asignar id_user automáticamente si no existe
            if (!$provider->id_user) {
                $provider->id_user = auth()->id() ?? 1;
            }
        });
    }
}
