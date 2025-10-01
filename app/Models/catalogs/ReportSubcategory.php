<?php

namespace App\Models\Catalogs;

use App\Models\Reports\Report;
use Illuminate\Database\Eloquent\Model;

class ReportSubcategory extends Model
{
    //
    protected $table = 'report_subcategories';
    protected $fillable = [
        'name',
        'report_category_id',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
