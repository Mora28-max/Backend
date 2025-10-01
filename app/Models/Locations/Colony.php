<?php

namespace App\Models\Locations;

use Illuminate\Database\Eloquent\Model;

class Colony extends Model
{
    protected $table = 'colonies';
    protected $fillable = ['name'];

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }
}
