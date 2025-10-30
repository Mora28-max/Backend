<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Inventory\Category;
use App\Models\Inventory\GoodsStatus;
use App\Models\Inventory\Provider;

class Goods extends Model
{
    use HasFactory;

    protected $table = 'goods';
   

    protected $fillable = [
        'name',
        'code_goods',
        'description',
        'brand',
        'id_status',
        'stock',
        'id_category',
        'url_evidence',
        'id_user',
        'id_provider'
    ];

    // Evento para generar código autoincremental
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($goods) {
            // Obtener el último código
            $lastGoods = self::latest('id')->first();
            if ($lastGoods) {
                // Sacamos la parte numérica del código
                $lastNumber = (int) str_replace('BIEN-', '', $lastGoods->code_goods);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            // Formatear con ceros a la izquierda
            $goods->code_goods = 'BIEN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    // Relaciones
    public function status()
    {
        return $this->belongsTo(GoodsStatus::class, 'id_status' , 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category' , 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user' , 'id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'id_provider' , 'id');
    }
}
