<?php

namespace App\Models\Reports;

use App\Models\User;
use App\Models\Reports\Report;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Note extends Model
{
    //
    use LogsActivity;

    protected $table = 'report_notes';

    protected $fillable = [
        'report_id',
        'description',
        'url_evidence',
        'user_id',
        'format_evidence',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Note')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
