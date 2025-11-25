<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMaterial extends Model
{
    use HasFactory;

   protected $table = 'order_materials';

    protected $fillable = [
        'id_provider',
        'subtotal',
        'vat',
        'id_user',
    ];

    // Proveedor
    public function provider()
    {
        return $this->belongsTo(\App\Models\Inventory\Provider::class, 'id_provider');
    }

    // Usuario
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }

    // Items
    public function items()
    {
        return $this->hasMany(OrderMaterialItem::class, 'order_id');
    }
}