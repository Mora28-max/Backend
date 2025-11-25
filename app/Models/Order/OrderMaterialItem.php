<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMaterialItem extends Model
{
    use HasFactory;

    protected $table = 'order_material_items';

    protected $fillable = [
        'order_id',
        'material_id',
        'quantity',
        'price',
        'total'
    ];

    // Orden
    public function order()
    {
        return $this->belongsTo(OrderMaterial::class, 'order_id');
    }

    // Material
    public function material()
    {
        return $this->belongsTo(\App\Models\Inventory\InventoryMaterial::class, 'material_id' , 'id');
    }
}