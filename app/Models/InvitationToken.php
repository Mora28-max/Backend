<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class InvitationToken extends Model
{
    //
    use LogsActivity;

    protected $fillable = [
        'email',
        'token',
        'used',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('InvitationToken')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
