<?php

namespace App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order\PurchaseOrderItem;
use App\Models\Inventory\Provider;
use App\Models\User;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'area_requester',
        'subtotal',
        'vat',
        'total',
        'delivery_date',
        'user_id',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
