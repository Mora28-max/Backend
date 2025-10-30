<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\PersonType;
use Illuminate\Http\Request;

class PersonTypeController extends Controller
{
 // Listar todos los tipos de persona
    public function index()
    {
        $types = PersonType::all();
        return response()->json(['data' => $types]);
    }

    // Crear un tipo de persona
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:person_types',
        ]);

        $type = PersonType::create($data);
        return response()->json(['message' => 'Tipo de persona creado', 'data' => $type], 201);
    }

    // Mostrar un tipo específico
    public function show($id)
    {
        $type = PersonType::find($id);
        if (!$type) return response()->json(['message' => 'No encontrado'], 404);
        return response()->json(['data' => $type]);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $type = PersonType::find($id);
        if (!$type) return response()->json(['message' => 'No encontrado'], 404);

        $data = $request->validate([
            'name' => 'required|string|max:100|unique:person_types,name,'.$id,
        ]);

        $type->update($data);
        return response()->json(['message' => 'Tipo de persona actualizado', 'data' => $type]);
    }

    // Eliminar
    public function destroy($id)
    {
        $type = PersonType::find($id);
        if (!$type) return response()->json(['message' => 'No encontrado'], 404);

        $type->delete();
        return response()->json(['message' => 'Tipo de persona eliminado']);
    }
}
