<?php

namespace App\Models\Catalogs;

use App\Models\Notice\Notice;
use Illuminate\Database\Eloquent\Model;

class NoticeTypes extends Model
{
    //
    protected $table = 'notice_types';

    public function notices()
    {
        return $this->hasMany(Notice::class);
    }
}
