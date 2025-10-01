<?php

namespace App\Models\Catalogs;

use App\Models\Reports\Report;
use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    //
    protected $table = 'report_categories';
    protected $fillable = [
        'name',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
