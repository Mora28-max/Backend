<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customers\Committee;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\StoreCommitteeRequest;
use App\Http\Requests\Customers\UpdateCommitteeRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommitteeController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Committee::class);
        $committees = Committee::select('id', 'code', 'name', 'notes')->get();
        return response(['data' => $committees], 200);
    }

    public function store(StoreCommitteeRequest $request)
    {
        try {
            $this->authorize('create', Committee::class);
            $committee = Committee::create($request->validated());
            return response([
                'message' => 'El comité ha sido creado correctamente.',
                'data' => $committee,
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear el comité.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show(Committee $committee)
    {
        $this->authorize('view', $committee);
        $committee = $committee->only(['id', 'code', 'name', 'notes']);
        return response(['data' => $committee], 200);
    }

    public function update(UpdateCommitteeRequest $request, Committee $committee)
    {
        try {
            $this->authorize('update', $committee);
            $committee->update($request->validated());
            return response([
                'message' => 'El comité ha sido actualizado correctamente.',
                'data' => $committee,
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar el comité.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Committee $committee)
    {
        try {
            $this->authorize('delete', $committee);
            $committee->delete();
            return response([
                'message' => 'El comité ha sido eliminado correctamente.',
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar el comité.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
