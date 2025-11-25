<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Provider;
use App\Models\Inventory\PersonType;
use App\Models\Inventory\Status;
use App\Http\Requests\Inventory\StoreProviderRequest;
use App\Http\Requests\Inventory\UpdateProviderRequest;
use App\Http\Resources\Provider\ProviderResource;
use App\Http\Resources\Provider\ProviderCollection;
use App\Helpers\UploadDataToCloudinary;

class ProviderController extends Controller
{
    // Listar proveedores con Resource Collection
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Provider::with(['personType', 'status', 'user'])->orderBy('id', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('rfc', 'LIKE', "%{$search}%");
            });
        }

        $providers = $query->paginate(25)->withQueryString();

        return new ProviderCollection($providers);
    }

    // Mostrar un proveedor
    public function show($id)
    {
        $provider = Provider::with(['personType', 'status', 'user'])->find($id);

        if (!$provider) {
            return response()->json([
                'message' => 'Proveedor no encontrado',
            ], 404);
        }

        return new ProviderResource($provider);
    }

    // Crear proveedor
    public function store(StoreProviderRequest $request)
    {
        $validated = $request->validated();

        // Guardar usuario que creó el registro
        $validated['user_id'] = auth()->id() ?? 1;

        // Código secuencial
        $last = Provider::latest('id')->first();
        $validated['code_provider'] = $last ? $last->id + 1 : 1;

        // Validar tipo proveedor
        if (!in_array($validated['id_type'], [0, 1])) {
            return response()->json(['error' => 'Tipo de proveedor inválido'], 422);
        }

        // Subida de evidencia
        if ($request->hasFile('url_evidence')) {
            $result = UploadDataToCloudinary::uploadImage(
                'provider-'.$validated['code_provider'],
                $request->file('url_evidence'),
                'providers/evidence'
            );
            $validated['url_evidence'] = $result['secure_url'];
            $validated['public_id_evidence'] = $result['public_id'];
        }

        $provider = Provider::create($validated);
        $provider->load(['personType', 'status', 'user']);

        return response()->json([
            'message' => 'Proveedor creado correctamente',
            'data' => new ProviderResource($provider),
        ], 201);
    }

    // Actualizar proveedor
    public function update(UpdateProviderRequest $request, $id)
    {
        $provider = Provider::findOrFail($id);
        $data = $request->validated();

        if (isset($data['id_type']) && !in_array($data['id_type'], [0, 1])) {
            return response()->json(['error' => 'Tipo de proveedor inválido'], 422);
        }

        // Actualizar evidencia
        if ($request->hasFile('url_evidence')) {

            if ($provider->public_id_evidence) {
                UploadDataToCloudinary::removeFile($provider->public_id_evidence);
            }

            $result = UploadDataToCloudinary::uploadImage(
                'provider-'.$provider->id,
                $request->file('url_evidence'),
                'providers/evidence'
            );

            $data['url_evidence'] = $result['secure_url'];
            $data['public_id_evidence'] = $result['public_id'];
        }

        $provider->update($data);
        $provider->load(['personType', 'status', 'user']);

        return response()->json([
            'message' => 'Proveedor actualizado correctamente',
            'data' => new ProviderResource($provider),
        ]);
    }

    // Eliminar proveedor completo
    public function destroy($id)
    {
        $provider = Provider::findOrFail($id);

        if ($provider->public_id_evidence) {
            UploadDataToCloudinary::removeFile($provider->public_id_evidence);
        }

        $provider->delete();

        return response()->json([
            'message' => 'Proveedor y archivos eliminados correctamente.'
        ]);
    }

    // Eliminar solo la evidencia
    public function deleteEvidence($id)
    {
        $provider = Provider::findOrFail($id);

        if (!$provider->public_id_evidence) {
            return response()->json(['message' => 'No hay evidencia que eliminar'], 400);
        }

        $deleted = UploadDataToCloudinary::removeFile($provider->public_id_evidence);

        if ($deleted) {
            $provider->update([
                'url_evidence' => null,
                'public_id_evidence' => null
            ]);
            return response()->json(['message' => 'Evidencia eliminada correctamente']);
        }

        return response()->json(['message' => 'No se pudo eliminar la evidencia en Cloudinary']);
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

    // Obtener tipos de persona
    public function personTypes()
    {
        return response()->json(['data' => PersonType::all()]);
    }

    // Obtener estados
    public function statuses()
    {
        return response()->json(['data' => Status::all()]);
    }
}
