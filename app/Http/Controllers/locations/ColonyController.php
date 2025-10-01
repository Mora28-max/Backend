<?php

namespace App\Http\Controllers\Locations;

use App\Models\Locations\Colony;
use App\Http\Controllers\Controller;
use App\Services\Locations\ColonyService;
use App\Http\Resources\Locations\ColonyCollection;
use App\Http\Requests\Locations\Colony\StoreColonyRequest;
use App\Http\Requests\Locations\Colony\UpdateColonyRequest;
use App\Http\Resources\Locations\ColonyResource;

class ColonyController extends Controller
{

    public function __construct(protected ColonyService $colony_service) {}

    public function index()
    {
        $colonies = Colony::select('id', 'name')->get();
        return new ColonyCollection($colonies);
    }

    public function store(StoreColonyRequest $request)
    {
        try {
            $colony = $this->colony_service->createColony($request->validated());
            return response([
                'message' => 'Colonia creada con éxito',
                'data' => new ColonyResource($colony)
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear la colonia.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function update(UpdateColonyRequest $request, Colony $colony)
    {
        try {
            $colony = $this->colony_service->updateColony($colony, $request->validated());
            return response([
                'message' => 'Colonia actualizada con éxito',
                'data' => new ColonyResource($colony->fresh())
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar la colonia.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Colony $colony)
    {
        try {
            $this->colony_service->deleteColony($colony);
            return response(['message' => 'Colonia eliminada con éxito'], 204);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar la colonia.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
