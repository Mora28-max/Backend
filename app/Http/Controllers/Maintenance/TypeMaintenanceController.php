<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance\TypeMaintenance;

class TypeMaintenanceController extends Controller
{
    /**
     * Listar todos los tipos de mantenimiento
     */
    public function index()
    {
        $types = TypeMaintenance::all();
        $data = $types->map(fn($type) => $this->transformTypeMaintenance($type));

        return response()->json(['data' => $data]);
    }

    /**
     * Mostrar un tipo de mantenimiento específico
     */
    public function show($id)
    {
        $type = TypeMaintenance::find($id);

        if (!$type) {
            return response()->json([
                'message' => 'Tipo de mantenimiento no encontrado',
                'id_busqueda' => $id
            ], 404);
        }

        return response()->json(['data' => $this->transformTypeMaintenance($type)]);
    }

    /**
     * Crear un tipo de mantenimiento
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $type = TypeMaintenance::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Tipo de mantenimiento registrado correctamente',
            'data' => $this->transformTypeMaintenance($type)
        ], 201);
    }

    /**
     * Actualizar un tipo de mantenimiento
     */
    public function update(Request $request, $id)
    {
        $type = TypeMaintenance::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $type->update(['name' => $request->name]);

        return response()->json([
            'message' => 'Tipo de mantenimiento actualizado correctamente',
            'data' => $this->transformTypeMaintenance($type)
        ]);
    }

    /**
     * Eliminar un tipo de mantenimiento
     */
    public function destroy($id)
    {
        $type = TypeMaintenance::findOrFail($id);
        $type->delete();

        return response()->json([
            'message' => 'Tipo de mantenimiento eliminado correctamente'
        ]);
    }

    /**
     * Transformar tipo de mantenimiento para respuesta limpia
     */
    private function transformTypeMaintenance(TypeMaintenance $type)
    {
        return [
            'id' => $type->id,
            'name' => $type->name,
            'created_at' => $type->created_at,
            'updated_at' => $type->updated_at,
        ];
    }
}
