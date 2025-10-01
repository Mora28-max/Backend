<?php

namespace App\Models\Notice;

use App\Models\User;
use App\Models\Customers\Customer;
use Spatie\Activitylog\LogOptions;
use App\Models\Pivots\NoticeReport;
use App\Models\Catalogs\NoticeTypes;
use App\Models\Catalogs\ProcessStatus;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Notice extends Model
{
    //
    use LogsActivity;
    protected $table = 'notices';
    protected $fillable = [
        'customer_id',
        'tracking_folio',
        'months_behind',
        'amount',
        'period',
        'process_status_id',
        'notice_type_id',
        'is_reported',
        'comment',
        'evidence',
        'cost',
        'user_id',
        'notification_date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Notice')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function processStatus()
    {
        return $this->belongsTo(ProcessStatus::class);
    }

    public function noticeType()
    {
        return $this->belongsTo(NoticeTypes::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function noticeReports()
    {
        return $this->hasMany(NoticeReport::class);
    }
}
