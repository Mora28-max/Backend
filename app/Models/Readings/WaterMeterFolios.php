<?php

namespace App\Models\Readings;

use App\Models\Customers\Customer;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class WaterMeterFolios extends Model
{
    protected $table = 'water_meter_folios';

    protected $fillable = [
        'customer_id',
        'folio_number',
        'installation_date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('MeterReadingSchedule')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
