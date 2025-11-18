<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Goods;
use App\Http\Requests\Inventory\StoreGoodsRequest;
use App\Http\Requests\Inventory\UpdateGoodsRequest;
use App\Http\Resources\Inventory\GoodsResource;
use App\Http\Resources\Inventory\GoodsCollection;
use App\Helpers\UploadDataToCloudinary;

class GoodsController extends Controller
{
    /**
     * Mostrar todos los bienes.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Goods::with(['status', 'category', 'provider']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code_goods', 'LIKE', "%{$search}%");
            });
        }

        $goods = $query->paginate(25)->withQueryString();

        return new GoodsCollection($goods);
    }

    /**
     * Mostrar un bien.
     */
    public function show($id)
    {
        $good = Goods::with(['status', 'category', 'provider'])->find($id);

        if (!$good) {
            return response()->json(['message' => 'Bien no encontrado'], 404);
        }

        return response()->json([
            'data' => new GoodsResource($good)
        ]);
    }

    /**
     * Crear un bien.
     */
    public function store(StoreGoodsRequest $request)
    {
        $validated = $request->validated();
        $validated['id_user'] = auth()->id() ?? 1;

        // Provider mapping
        if (isset($validated['provider_id'])) {
            $validated['id_provider'] = $validated['provider_id'];
            unset($validated['provider_id']);
        }

        $good = Goods::create($validated);

        /** ---- IMAGEN EVIDENCIA ---- **/
        if ($request->hasFile('url_evidence')) {

            $result = UploadDataToCloudinary::uploadImage(
                $good->id,
                $request->file('url_evidence'),
                'inventory-goods/evidence'
            );

            $good->url_evidence = $result['secure_url'];
            $good->public_id_evidence = $result['public_id'];
        }

        /** ---- SUBIDA DE PDF / DOCUMENTO ---- **/
        if ($request->hasFile('url_invoice')) {

            $result = UploadDataToCloudinary::uploadDocument(
                $good->id,
                $request->file('url_invoice'),
                'inventory-goods/invoices'
            );

            $good->url_invoice = $result['secure_url'];
            $good->public_id_invoice = $result['public_id'];
        }

        $good->save();
        $good->load(['status', 'category', 'provider']);

        return response()->json([
            'message' => 'Bien creado correctamente',
            'data' => new GoodsResource($good)
        ], 201);
    }

    /**
     * Actualizar un bien.
     */
    public function update(UpdateGoodsRequest $request, $id)
    {
        $good = Goods::findOrFail($id);
        $data = $request->validated();

        // Provider mapping
        if (isset($data['provider_id'])) {
            $data['id_provider'] = $data['provider_id'];
            unset($data['provider_id']);
        }

        /** ---- ACTUALIZAR IMAGEN EVIDENCIA ---- **/
        if ($request->hasFile('url_evidence')) {

            if ($good->public_id_evidence) {
                UploadDataToCloudinary::removeFile($good->public_id_evidence);
            }

            $result = UploadDataToCloudinary::uploadImage(
                $good->id,
                $request->file('url_evidence'),
                'inventory-goods/evidence'
            );

            $data['url_evidence'] = $result['secure_url'];
            $data['public_id_evidence'] = $result['public_id'];
        }

        /** ---- ACTUALIZAR DOCUMENTO / PDF ---- **/
        if ($request->hasFile('url_invoice')) {

            if ($good->public_id_invoice) {
                UploadDataToCloudinary::removeFile($good->public_id_invoice);
            }

            $result = UploadDataToCloudinary::uploadDocument(
                $good->id,
                $request->file('url_invoice'),
                'inventory-goods/invoices'
            );

            $data['url_invoice'] = $result['secure_url'];
            $data['public_id_invoice'] = $result['public_id'];
        }

        $good->update($data);
        $good->load(['status', 'category', 'provider']);

        return response()->json([
            'message' => 'Bien actualizado correctamente',
            'data' => new GoodsResource($good)
        ]);
    }

    /**
     * Eliminar un bien.
     */
    public function destroy($id)
    {
        $good = Goods::findOrFail($id);

        if ($good->public_id_evidence) {
            UploadDataToCloudinary::removeFile($good->public_id_evidence);
        }

        if ($good->public_id_invoice) {
            UploadDataToCloudinary::removeFile($good->public_id_invoice);
        }

        $good->delete();

        return response()->json(['message' => 'Bien eliminado correctamente']);
    }

    /**
     * Eliminar solo la imagen del bien.
     */
    public function deleteImage($id)
    {
        $good = Goods::findOrFail($id);

        if (!$good->public_id_evidence) {
            return response()->json(['message' => 'No hay imagen que eliminar'], 400);
        }

        $deleted = UploadDataToCloudinary::removeFile($good->public_id_evidence);

        $good->update([
            'url_evidence' => null,
            'public_id_evidence' => null
        ]);

        if ($deleted) {
            return response()->json(['message' => 'Imagen eliminada correctamente']);
        }

        return response()->json([
            'message' => 'Imagen eliminada de BD pero puede seguir en Cloudinary'
        ]);
    }

    /**
     * Eliminar solo el PDF / factura.
     */
    public function deleteInvoice($id)
    {
        $good = Goods::findOrFail($id);

        if (!$good->public_id_invoice) {
            return response()->json(['message' => 'No hay factura que eliminar'], 400);
        }

        $deleted = UploadDataToCloudinary::removeFile($good->public_id_invoice);

        $good->update([
            'url_invoice' => null,
            'public_id_invoice' => null
        ]);

        if ($deleted) {
            return response()->json(['message' => 'Factura eliminada correctamente']);
        }

        return response()->json([
            'message' => 'Factura eliminada en BD pero pudo quedar en Cloudinary'
        ]);
    }

    /**
     * Transformar bien para respuesta limpia.
     */
    private function transformGood(Goods $good)
    {
        return [
            'id' => $good->id,
            'name' => $good->name,
            'description' => $good->description,
            'brand' => $good->brand,
            'stock' => $good->stock,
            'id_status' => $good->id_status,
            'id_category' => $good->id_category,
            'id_provider' => $good->id_provider,
            'id_user' => $good->id_user,
            'code_goods' => $good->code_goods,
              'url_evidence' => $good->url_evidence,
            'created_at' => $good->created_at,
            'updated_at' => $good->updated_at,
            'status' => $good->status ? ['id' => $good->status->id, 'name' => $good->status->name] : null,
            'category' => $good->category ? ['id' => $good->category->id, 'name' => $good->category->name] : null,
            'provider' => $good->provider ? ['id' => $good->provider->id, 'name' => $good->provider->name] : null,
        ];
    }
}
