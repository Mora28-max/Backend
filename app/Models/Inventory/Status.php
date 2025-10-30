<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    protected $table = 'statuses';

    protected $fillable = ['name'];

    // Relación con proveedores
    public function providers()
    {
        return $this->hasMany(Provider::class, 'status_id', 'id');
    }
    
}
