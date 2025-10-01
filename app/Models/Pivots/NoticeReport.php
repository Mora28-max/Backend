<?php

namespace App\Models\Pivots;

use App\Models\Notice\Notice;
use App\Models\Reports\Report;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class NoticeReport extends Model
{
    //
    protected $table = 'notices_reports';

    protected $fillable = [
        'notice_id',
        'report_id',
        'customer_id',
        'finished_process',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('NoticeReport')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'notice_id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
