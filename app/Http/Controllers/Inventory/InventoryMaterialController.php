<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryMaterial;
use App\Http\Requests\Inventory\StoreInventoryMaterialRequest;
use App\Http\Requests\Inventory\UpdateInventoryMaterialRequest;
use App\Http\Requests\Inventory\DeleteInventoryMaterialRequest;

class InventoryMaterialController extends Controller
{
    /**
     * Mostrar todos los materiales.
     */
    public function index()
    {
        $materials = InventoryMaterial::with(['provider', 'unity'])->get();

        $data = $materials->map(function ($material) {
            return $this->transformMaterial($material);
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Crear un nuevo material.
     */
    public function store(StoreInventoryMaterialRequest $request)
    {
        $validated = $request->validated();

        // Asignar automáticamente el usuario que crea el material
        $validated['id_user'] = auth()->id(); // <-- aquí se agrega

        $material = InventoryMaterial::create($validated);

        $material->load(['provider', 'unity']);

        return response()->json([
            'message' => 'Material creado correctamente',
            'data' => $this->transformMaterial($material)
        ], 201);
    }

    /**
     * Mostrar un material específico.
     */
    public function show($id)
    {
        $material = InventoryMaterial::with(['provider', 'unity'])->find($id);

        if (!$material) {
            return response()->json([
                'message' => 'Material no encontrado',
                
            ], 404);
        }

        return response()->json([
            'data' => $this->transformMaterial($material)
        ]);
    }

    /**
     * Actualizar un material existente.
     */
    public function update(UpdateInventoryMaterialRequest $request, $id)
    {
        $material = InventoryMaterial::findOrFail($id);

        $material->update($request->validated());

        $material->load(['provider', 'unity']);

        return response()->json([
            'message' => 'Material actualizado correctamente',
            'data' => $this->transformMaterial($material)
        ]);
    }

    /**
     * Eliminar un material.
     */
    public function destroy(DeleteInventoryMaterialRequest $request, $id)
    {
        $material = InventoryMaterial::findOrFail($id);
        $material->delete();

        return response()->json(['message' => 'Material eliminado correctamente']);
    }

    /**
     * Transformar material para respuesta limpia.
     */
    private function transformMaterial(InventoryMaterial $material)
    {
        return [
            'id' => $material->id,
            'name' => $material->name,
            'code_materials' => $material->code_materials,
            'stock' => $material->stock,
            'description' => $material->description,
            'cost' => $material->cost,
            'url_evidence' => $material->url_evidence,
            'provider_id' => $material->provider_id,
            'unit_type_id' => $material->unit_type_id,
            'created_at' => $material->created_at,
            'updated_at' => $material->updated_at,
            'provider' => [
                'id' => $material->provider->id,
                'name' => $material->provider->name,
            ],
            'unity' => [
                'id' => $material->unity->id,
                'name' => $material->unity->name,
                'abbreviation' => $material->unity->abbreviation,
            ],
        ];
    }
}
