<?php

namespace App\Traits;

trait HasCustomerRelations
{
    protected function customerRelations(): array
    {
        return [
            'zone:id,name,colony_id',
            'zone.colony:id,name',
            'customerType:id,name',
            'useOfType:id,name',
            'serviceType:id,name',
            'serviceStatus:id,name',
            'classificationType:id,name',
            'additionalObservation:id,code,description'
        ];
    }
}
