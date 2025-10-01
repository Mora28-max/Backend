<?php

namespace App\Services\Locations;

use App\Models\Locations\Colony;

class ColonyService
{
    public function createColony(array $data): Colony
    {
        return Colony::create($data);
    }

    public function updateColony(Colony $colony, array $data): Colony
    {
        $colony->update($data);
        return $colony;
    }

    public function deleteColony(Colony $colony): bool
    {
        return $colony->delete();
    }
}
