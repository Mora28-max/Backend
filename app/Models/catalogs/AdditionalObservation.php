<?php

namespace App\Models\Catalogs;

use App\Models\Customers\Customer;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AdditionalObservation extends Model
{
    //
    use LogsActivity;
    protected $table = 'additional_observations';
    protected $fillable = [
        'code',
        'description',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('AdditionalObservation')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
