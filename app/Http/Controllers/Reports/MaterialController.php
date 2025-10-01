<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Models\Reports\Material;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreMaterialRequest;
use App\Http\Resources\Report\MaterialCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MaterialController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Material::class);

        if (empty($request->search) || strlen($request->search) < 5)
            return response(['message' => 'Debe ingresar un nombre de al menos 5 caracteres'], 400);

        $materials = Material::where('name', 'like', '%' . $request->search . '%')
            ->limit(5)
            ->get();

        if ($materials->isEmpty())
            return response(['message' => 'No se encontraron coincidencias.'], 400);

        return new MaterialCollection($materials);
    }

    public function store(StoreMaterialRequest $request)
    {
        try {
            $this->authorize('create', Material::class);
            $material = Material::create($request->validated());
            return response([
                'message' => 'El material ha sido creado correctamente.',
                'material' => $material,
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear el material.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Material $material)
    {
        try {
            $this->authorize('delete', $material);
            $material->delete();
            return response(['message' => 'Material eliminado correctamente.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar el material.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
