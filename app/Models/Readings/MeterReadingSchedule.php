<?php

namespace App\Models\Readings;

use App\Models\User;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Payments\MonthlyServiceCharge;

class MeterReadingSchedule extends Model
{
    //
    use LogsActivity;

    protected $table = 'meter_reading_schedule';

    protected $fillable = [
        'user_id',
        'montly_service_charge_id',
        'meter_reading',
        'evidence',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('MeterReadingSchedule')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function montlyServiceCharge()
    {
        return $this->belongsTo(MonthlyServiceCharge::class);
    }
}
