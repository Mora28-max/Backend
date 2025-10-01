<?php

namespace App\Models\Reports;

use App\Models\Reports\Report;
use App\Models\Reports\Unities;
use App\Models\Reports\Material;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MaterialUsed extends Model
{
    //
    use LogsActivity;

    protected $table = 'used_materials';

    protected $fillable = [
        'material_id',
        'report_id',
        'unit_id',
        'quantity',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Material Used')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unities::class);
    }
}
