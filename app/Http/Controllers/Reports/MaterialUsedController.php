<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reports\MaterialUsed;
use App\Http\Requests\Report\StoreMaterialUsedRequest;
use App\Http\Resources\Report\MaterialUsedResource;
use App\Http\Resources\Report\MaterialUsedCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MaterialUsedController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', MaterialUsed::class);
        $materials = MaterialUsed::where('report_id', $request->report_id)->get();
        return new MaterialUsedCollection($materials);
    }

    public function store(StoreMaterialUsedRequest $request)
    {
        try {
            $this->authorize('create', MaterialUsed::class);
            $material_used = MaterialUsed::create($request->validated());
            return response([
                'message' => 'El material ha sido agregado correctamente.',
                'data' => new MaterialUsedResource($material_used),
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al agregar el material.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(MaterialUsed $material_used)
    {
        try {
            $this->authorize('delete', $material_used);
            $material_used->delete();
            return response(['message' => 'El material usado ha sido eliminado correctamente.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar el material usado.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
