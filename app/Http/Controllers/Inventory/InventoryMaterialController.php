<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryMaterial;
use App\Http\Requests\Inventory\StoreInventoryMaterialRequest;
use App\Http\Requests\Inventory\UpdateInventoryMaterialRequest;
use App\Http\Requests\Inventory\DeleteInventoryMaterialRequest;
use App\Http\Resources\Inventory\InventoryMaterialCollection;
use App\Http\Resources\Inventory\InventoryMaterialResource;
use App\Helpers\UploadDataToCloudinary;

class InventoryMaterialController extends Controller
{
    // Mostrar todos los materiales con búsqueda
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = InventoryMaterial::with(['provider', 'unity']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code_materials', 'LIKE', "%{$search}%");
            });
        }

        $materials = $query->paginate(25)->withQueryString();

        return new InventoryMaterialCollection($materials);
    }

    // Materiales con stock bajo
    public function lowStock()
    {
        $materials = InventoryMaterial::with(['provider', 'unity'])
            ->lowStock()
            ->orderBy('stock', 'asc')
            ->get();

        return InventoryMaterialResource::collection($materials);
    }

    // Crear material
    public function store(StoreInventoryMaterialRequest $request)
    {
        $validated = $request->validated();
        $validated['id_user'] = auth()->id();

        $material = InventoryMaterial::create($validated);

        // Imagen evidencia
        if ($request->hasFile('url_evidence')) {
            $result = UploadDataToCloudinary::uploadImage(
                $material->id,
                $request->file('url_evidence'),
                'inventory-materials/evidence'
            );
            $material->url_evidence = $result['secure_url'];
            $material->public_id_evidence = $result['public_id'];
        }

        // Factura / documento
        if ($request->hasFile('url_invoice')) {
            $result = UploadDataToCloudinary::uploadDocument(
                $material->id,
                $request->file('url_invoice'),
                'inventory-materials/invoices'
            );
            $material->url_invoice = $result['secure_url'];
            $material->public_id_invoice = $result['public_id'];
        }

        $material->save();
        $material->load(['provider', 'unity']);

        return response()->json([
            'message' => 'Material creado correctamente',
            'data' => new InventoryMaterialResource($material),
        ], 201);
    }

    // Mostrar un material
    public function show($id)
    {
        $material = InventoryMaterial::with(['provider', 'unity'])->find($id);

        if (!$material) {
            return response()->json([
                'message' => 'Material no encontrado',
            ], 404);
        }

        return response()->json([
            'data' => new InventoryMaterialResource($material),
        ]);
    }

    // Actualizar material
    public function update(UpdateInventoryMaterialRequest $request, $id)
    {
        $material = InventoryMaterial::findOrFail($id);
        $data = $request->validated();

        // Actualizar imagen evidencia
        if ($request->hasFile('url_evidence')) {

            if ($material->public_id_evidence) {
                UploadDataToCloudinary::removeFile($material->public_id_evidence);
            }

            $result = UploadDataToCloudinary::uploadImage(
                $material->id,
                $request->file('url_evidence'),
                'inventory-materials/evidence'
            );
            $data['url_evidence'] = $result['secure_url'];
            $data['public_id_evidence'] = $result['public_id'];
        }

        // Actualizar factura
        if ($request->hasFile('url_invoice')) {

            if ($material->public_id_invoice) {
                UploadDataToCloudinary::removeFile($material->public_id_invoice);
            }

            $result = UploadDataToCloudinary::uploadDocument(
                $material->id,
                $request->file('url_invoice'),
                'inventory-materials/invoices'
            );
            $data['url_invoice'] = $result['secure_url'];
            $data['public_id_invoice'] = $result['public_id'];
        }

        $material->update($data);
        $material->load(['provider', 'unity']);

        return response()->json([
            'message' => 'Material actualizado correctamente',
            'data' => new InventoryMaterialResource($material),
        ]);
    }

    // Eliminar material completo
    public function destroy(DeleteInventoryMaterialRequest $request, $id)
    {
        $material = InventoryMaterial::findOrFail($id);

        if ($material->public_id_evidence) {
            UploadDataToCloudinary::removeFile($material->public_id_evidence);
        }

        if ($material->public_id_invoice) {
            UploadDataToCloudinary::removeFile($material->public_id_invoice);
        }

        $material->delete();

        return response()->json([
            'message' => 'Material y archivos eliminados correctamente.'
        ]);
    }

    // Eliminar solo imagen
    public function deleteImage($id)
    {
       

    $material = InventoryMaterial::findOrFail($id);

    if (!$material->public_id_evidence) {
        return response()->json(['message' => 'No hay imagen que eliminar'], 400);
    }

    // Usar el public_id guardado para eliminar en Cloudinary
    $deleted = UploadDataToCloudinary::removeFile($material->public_id_evidence);

    if ($deleted) {
        $material->update([
            'url_evidence' => null,
            'public_id_evidence' => null
        ]);
        return response()->json(['message' => 'Imagen eliminada correctamente de Cloudinary y la BD']);
    }

    return response()->json(['message' => 'No se pudo eliminar la imagen en Cloudinary']);
}


    // Eliminar solo factura
   public function deleteInvoice($id)
{
    $material = InventoryMaterial::findOrFail($id);

    if (!$material->public_id_invoice) {
        return response()->json(['message' => 'No hay factura que eliminar'], 400);
    }

    // Usar el public_id guardado para eliminar en Cloudinary
    $deleted = UploadDataToCloudinary::removeFile($material->public_id_invoice);

    // Actualizar la base de datos siempre
    $material->update([
        'url_invoice' => null,
        'public_id_invoice' => null
    ]);

    if ($deleted) {
        return response()->json(['message' => 'Factura eliminada correctamente de Cloudinary y la BD']);
    }

    \Log::warning('No se pudo eliminar la factura en Cloudinary, pero se actualizó la BD', [
        'material_id' => $id,
        'public_id' => $material->public_id_invoice
    ]);

    return response()->json(['message' => 'Factura eliminada de la base de datos, pero pudo quedar en Cloudinary']);
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
            'stock_min' => $material->stock_min,
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
