<?php

namespace App\Models\Catalogs;

use App\Models\Reports\Report;
use Illuminate\Database\Eloquent\Model;

class ReportChildSubcategory extends Model
{
    //
    protected $table = 'report_child_subcategories';
    protected $fillable = [
        'name',
        'report_subcategory_id',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
