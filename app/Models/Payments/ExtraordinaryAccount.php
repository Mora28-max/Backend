<?php

namespace App\Models\Payments;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class ExtraordinaryAccount extends Model
{
    //
    protected $table = 'extraordinary_accounts';

    protected $fillable = [
        'name',
        'code',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Extraordinary Accounts')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
