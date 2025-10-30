<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Goods;
use App\Models\User;

class MaintenanceHistory extends Model
{
    use HasFactory;

    protected $table = 'maintenance_histories';

    protected $fillable = [
         // ahora será solo números: 1, 2, 3...
        'id_goods',
        'date',
        'observations',
        'cost',
        'next_maintenance_date',
        'id_type_maintenance',
        'id_user',
    ];

    /**
     * Relación con el tipo de mantenimiento
     */
    public function typeMaintenance()
    {
         return $this->belongsTo(TypeMaintenance::class, 'id_type_maintenance', 'id');
    }

    /**
     * Relación con el bien (goods)
     */
    public function goods()
    {
        return $this->belongsTo(Goods::class, 'id_goods', 'id');
    }

    /**
     * Relación con el usuario que registró el mantenimiento
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * Genera automáticamente el código secuencial (1, 2, 3...)
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($maintenance) {
            if (!$maintenance->code) {
                $last = self::latest('id')->first();
                $maintenance->code = $last ? $last->id + 1 : 1;
            }

             // Asignar id_user automáticamente si no existe
        if (!$maintenance->id_user) {
            $maintenance->id_user = auth()->id() ?? 1;
        }

        // Calcular next_maintenance_date automáticamente (+1 mes)
        if ($maintenance->date && !$maintenance->next_maintenance_date) {
            $maintenance->next_maintenance_date = \Carbon\Carbon::parse($maintenance->date)->addMonth();
        }
        });
    }
}
