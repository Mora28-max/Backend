<?php

namespace App\Traits;

trait HasReportRelations
{
    protected function reportRelations(): array
    {
        return [
            'reportCategory:id,name',
            'reportSubcategory:id,name',
            'reportPriority:id,name',
            'processStatus:id,name',
            'reportChildSubcategory:id,name',
            'processStatus:id,name',
        ];
    }
}
