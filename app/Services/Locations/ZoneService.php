<?php

namespace App\Services\Locations;

use App\Models\Locations\Zone;

class ZoneService
{

    public function createZone(array $data): Zone
    {
        return Zone::create($data);
    }

    public function updateZone(Zone $zone, array $data): Zone
    {
        $zone->update($data);
        return $zone;
    }

    public function deleteZone(Zone $zone): void
    {
        $zone->delete();
    }
}
