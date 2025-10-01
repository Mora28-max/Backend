<?php

namespace App\Models\WaterTank;

use Illuminate\Database\Eloquent\Model;

class WaterTank extends Model
{
    protected $table = 'water_tank';
    protected $fillable = ['name'];
}
