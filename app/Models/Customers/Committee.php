<?php

namespace App\Models\Customers;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Committee extends Model
{
    //
    use LogsActivity;

    protected $table = 'committees';

    protected $fillable = [
        'code',
        'name',
        'notes',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Committee')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
