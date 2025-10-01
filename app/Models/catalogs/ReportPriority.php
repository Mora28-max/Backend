<?php

namespace App\Models\Catalogs;

use App\Models\Reports\Report;
use Illuminate\Database\Eloquent\Model;

class ReportPriority extends Model
{
    //
    protected $table = 'report_priorities';

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
