<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Provider;
use App\Models\Reports\Unities;
use App\Models\User;

class InventoryMaterial extends Model
{
     use HasFactory;

    protected $table = 'inventory_materials';
    

    protected $fillable = [
        'name',
        'code_materials',
        'stock',
        'stock_min', // ✅ agregamos stock mínimo
        'description',
        'cost',
        'url_evidence',
        'url_invoice',
        'provider_id',
        'unit_type_id',
        'id_user',
    ];

    /**
     * Relación con el proveedor
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }

    /**
     * Relación con la unidad de medida
     */
    public function unit()
    {
        return $this->belongsTo(Unities::class, 'unit_type_id', 'id');
    }
    /**
     * Relación con el usuario que registró el material
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

      /**
     * Scope: materiales con bajo stock
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_min');
    }


      /**
     * Genera automáticamente el código del material (MAT-001, MAT-002, etc.)
     */
    protected static function boot()
{
    parent::boot();

    static::creating(function ($material) {

        if (!$material->code_materials) {
            // Buscar el último código válido
            $lastMaterial = self::whereNotNull('code_materials')
                                ->orderBy('id', 'desc')
                                ->first();

            if ($lastMaterial && preg_match('/MAT-(\d+)/', $lastMaterial->code_materials, $matches)) {
                $lastNumber = (int) $matches[1];
            } else {
                $lastNumber = 0;
            }

            $nextNumber = $lastNumber + 1;
            $material->code_materials = 'MAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        // Asignar id_user automáticamente si no viene
        if (!$material->id_user) {
            $material->id_user = auth()->id() ?? 1;
        }
           
        });
    }
}
