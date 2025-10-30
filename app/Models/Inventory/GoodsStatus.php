<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsStatus extends Model
{
    use HasFactory;

    protected $table = 'goods_status';

    protected $fillable = ['name'];

    public function goods()
    {
        return $this->hasMany(Goods::class, 'id_status' , 'id');
    }
}
