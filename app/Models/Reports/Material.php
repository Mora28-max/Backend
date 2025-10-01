<?php

namespace App\Models\Reports;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalogs\ClassificationOfMaterials;

class Material extends Model
{
    //
    protected $table = 'hydraulic_repair_materials';

    protected $fillable = [
        'name',
        'class_of_materials_id',
    ];

    public function classOfMaterials()
    {
        return $this->belongsTo(ClassificationOfMaterials::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Hydraulic Repair Materials')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
