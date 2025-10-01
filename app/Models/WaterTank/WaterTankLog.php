<?php

namespace App\Models\WaterTank;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class WaterTankLog extends Model
{
    protected $table = 'water_tank_logs';
    protected $fillable = ['water_tank_id', 'water_level', 'water_reception', 'log_date'];

    public function waterTank()
    {
        return $this->belongsTo(WaterTank::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Water Tank Logs')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
