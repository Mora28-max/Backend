<?php

namespace App\Models\Audit;

use App\Models\User;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class CashRegisterAudit extends Model
{
    //
    protected $table = 'cash_register_audit';

    protected $fillable = [
        'user_id',
        'receiver_user_id',
        'witness_user_id',
        'counted_cash',
        'system_cash',
        'digital_total',
        'discrepancy',
        'notes',
        'started_at',
        'ended_at',
        'details',
        'digital_details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function witness()
    {
        return $this->belongsTo(User::class, 'witness_user_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Cash Register Audit')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
