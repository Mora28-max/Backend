<?php

namespace App\Http\Controllers\Catalogs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalogs\AdditionalObservation;
use App\Http\Resources\Catalogs\AdditionalObservationResource;
use App\Http\Resources\Catalogs\AdditionalObservationCollection;
use App\Http\Requests\Catalogs\StoreAdditionalObservationResquest;
use App\Http\Requests\Catalogs\UpdateAdditionalObservationResquest;

class AdditionalObservationsController extends Controller
{
    public function index()
    {
        $data = AdditionalObservation::select('id', 'code', 'description')->get();
        return new AdditionalObservationCollection($data);
    }

    public function store(StoreAdditionalObservationResquest $request)
    {
        try {
            $additional_observation = AdditionalObservation::create($request->validated());
            return response([
                'message' => 'La observación ha sido creada correctamente.',
                'data' => new AdditionalObservationResource($additional_observation)
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'No autorizado.',
                'error' => $th->getMessage()
            ], 401);
        }
    }

    public function show(AdditionalObservation $additional_observation)
    {
        return new AdditionalObservationResource($additional_observation);
    }

    public function update(UpdateAdditionalObservationResquest $request, AdditionalObservation $additional_observation)
    {
        try {
            $additional_observation->update($request->validated());
            return response([
                'message' => 'La observación ha sido actualizada correctamente.',
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => 'No autorizado.',
                'error' => $th->getMessage()
            ], 401);
        }
    }

    public function destroy(AdditionalObservation $additional_observation)
    {
        try {
            $additional_observation->delete();
            return response([
                'message' => 'La observación ha sido eliminada correctamente.',
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'No autorizado.',
                'error' => $th->getMessage()
            ], 401);
        }
    }
}
