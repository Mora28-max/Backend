<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderGood extends Model
{
     protected $table = 'order_goods';

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
        return $this->hasMany(OrderGoodItem::class, 'order_id');
    }
}

