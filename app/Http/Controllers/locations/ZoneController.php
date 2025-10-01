<?php

namespace App\Http\Controllers\Locations;


use App\Models\Locations\Zone;
use App\Http\Controllers\Controller;
use App\Services\Locations\ZoneService;
use App\Http\Resources\Locations\ZoneResource;
use App\Http\Resources\Locations\ZoneCollection;
use App\Http\Requests\Locations\Zone\StoreZoneRequest;
use App\Http\Requests\Locations\Zone\UpdateZoneRequest;

class ZoneController extends Controller
{

    public function __construct(protected ZoneService $zone_service) {}

    public function index()
    {
        $zones = Zone::select('id', 'name', 'colony_id')->get();
        return new ZoneCollection($zones);
    }

    public function store(StoreZoneRequest $request)
    {
        //
        try {
            $zone = $this->zone_service->createZone($request->validated());
            return response([
                'message' => 'Zona creada exitosamente',
                'data' => new ZoneResource($zone->fresh())
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear la zona.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function update(UpdateZoneRequest $request, Zone $zone)
    {
        try {
            $zone = $this->zone_service->updateZone($zone, $request->validated());
            return response([
                'message' => 'Zona actualizada exitosamente',
                'data' => new ZoneResource($zone->fresh())
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar la zona.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Zone $zone)
    {
        try {
            $this->zone_service->deleteZone($zone);
            return response([
                'message' => 'Zona eliminada exitosamente'
            ], 204);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar la zona.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
