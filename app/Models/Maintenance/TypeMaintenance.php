<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeMaintenance extends Model
{
    use HasFactory;

    protected $table = 'type_maintenances';
    protected $fillable = ['name'];

    // Relación con maintenance_histories
    public function histories()
    {
        return $this->hasMany(MaintenanceHistory::class, 'id_type_maintenance', 'id');
    }
}
