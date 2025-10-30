<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Provider;
use App\Http\Requests\Inventory\StoreProviderRequest;
use App\Http\Requests\Inventory\UpdateProviderRequest;

class ProviderController extends Controller
{
    /**
     * Listar todos los proveedores
     */
    public function index()
    {
        $providers = Provider::with(['personType', 'status', 'user'])
            ->orderBy('id', 'asc')
            ->get();

        $data = $providers->map(fn($provider) => $this->transformProvider($provider));

        return response()->json(['data' => $data]);
    }

    /**
     * Mostrar un proveedor específico
     */
    public function show($id)
    {
        $provider = Provider::with(['personType', 'status', 'user'])->find($id);

        if (!$provider) {
            return response()->json([
                'message' => 'Proveedor no encontrado',
                'id_busqueda' => $id
            ], 404);
        }

        return response()->json(['data' => $this->transformProvider($provider)]);
    }

    /**
     * Crear un nuevo proveedor
     */
    public function store(StoreProviderRequest $request)
    {
        $validated = $request->validated();

        $validated['id_user'] = auth()->id() ?? 1;

        // 🔹 Generar código secuencial simple (1, 2, 3…)
        $lastProvider = Provider::latest('id')->first();
        $validated['code_provider'] = $lastProvider ? $lastProvider->id + 1 : 1;

        // Etiquetas del tipo de proveedor
       if (!in_array($validated['id_type'], [0, 1])) {
        return response()->json(['error' => 'Tipo de proveedor inválido'], 422);
    }
        $provider = Provider::create($validated);
        $provider->load(['personType', 'status', 'user']);

        return response()->json([
            'message' => 'Proveedor creado correctamente',
            'data' => $this->transformProvider($provider)
        ], 201);
    }

    /**
     * Actualizar un proveedor existente
     */
    public function update(UpdateProviderRequest $request, $id)
    {
        $provider = Provider::findOrFail($id);
        $validated = $request->validated();

        $typeLabels = [
            0 => 'Proveedor de bienes',
            1 => 'Proveedor de materiales',
        ];

        if (isset($validated['id_type'])) {
            $validated['id_type'] = [
                'type' => $validated['id_type'],
                'label' => $typeLabels[$validated['id_type']],
            ];
        }

        $provider->update($validated);
        $provider->load(['personType', 'status', 'user']);

        return response()->json([
            'message' => 'Proveedor actualizado correctamente',
            'data' => $this->transformProvider($provider)
        ]);
    }

    /**
     * Eliminar un proveedor
     */
    public function destroy($id)
    {
        $provider = Provider::findOrFail($id);
        $provider->delete();

        return response()->json(['message' => 'Proveedor eliminado correctamente']);
    }

    /**
     * Transformar proveedor para respuesta limpia
     */
    private function transformProvider(Provider $provider)
    {
        return [
            'id' => $provider->id,
            'code_provider' => $provider->code_provider,
            'name' => $provider->name,
            'rfc' => $provider->rfc,
            'address' => $provider->address,
            'phone' => $provider->phone,
            'email' => $provider->email,
            'url_evidence' => $provider->url_evidence,
            'id_user' => $provider->id_user,
            'id_type' => $provider->id_type,
            'person_type' => $provider->personType
                ? ['id' => $provider->personType->id, 'name' => $provider->personType->name]
                : null,
            'status' => $provider->status
                ? ['id' => $provider->status->id, 'name' => $provider->status->name]
                : null,
            'user' => $provider->user
                ? ['id' => $provider->user->id, 'firstname' => $provider->user->firstname]
                : null,
            'created_at' => $provider->created_at,
            'updated_at' => $provider->updated_at,
        ];
    }

    /**
     * Obtener tipos de persona
     */
    public function personTypes()
    {
        return response()->json(['data' => \App\Models\Inventory\PersonType::all()]);
    }

    /**
     * Obtener estados disponibles
     */
    public function statuses()
    {
        return response()->json(['data' => \App\Models\Inventory\Status::all()]);
    }
}
