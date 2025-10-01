<?php

namespace App\Models\Catalogs;

use App\Models\Notice\Notice;
use App\Models\Reports\Report;
use Illuminate\Database\Eloquent\Model;

class ProcessStatus extends Model
{
    //
    protected $table = 'process_status';

    public function notices()
    {
        return $this->hasMany(Notice::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
