<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderGoodItem extends Model
{
     protected $table = 'order_good_items';

    protected $fillable = [
        'order_id',
        'good_id',
        'quantity',
        'price',
        'total'
    ];

    // Orden
    public function order()
    {
        return $this->belongsTo(OrderGood::class, 'order_id');
    }

    // Bien
    public function good()
    {
        return $this->belongsTo(\App\Models\Inventory\Goods::class, 'good_id');
    }
}