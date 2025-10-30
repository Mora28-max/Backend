<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Goods;
use App\Http\Requests\Inventory\StoreGoodsRequest;
use App\Http\Requests\Inventory\UpdateGoodsRequest;

class GoodsController extends Controller
{
    /**
     * Mostrar todos los bienes.
     */
    public function index()
    {
        $goods = Goods::with(['status', 'category', 'provider'])->get();

        $data = $goods->map(fn($good) => $this->transformGood($good));

        return response()->json(['data' => $data]);
    }

    /**
     * Mostrar un bien específico.
     */
    public function show($id)
    {
        $good = Goods::with(['status', 'category', 'provider'])->find($id);

        if (!$good) {
            return response()->json([
                'message' => 'Bien no encontrado',
                'id_busqueda' => $id
            ], 404);
        }

        return response()->json(['data' => $this->transformGood($good)]);
    }

    /**
     * Crear un nuevo bien.
     */
    public function store(StoreGoodsRequest $request)
    {
        $validated = $request->validated();

        // Asignar id_user automáticamente, usar 1 si no hay usuario logueado
        $validated['id_user'] = auth()->id() ?? 1;

        // Mapear provider_id del request al nombre de columna de la tabla
        $validated['id_provider'] = $validated['provider_id'];
        unset($validated['provider_id']);

        $good = Goods::create($validated);
        $good->load(['status', 'category', 'provider']);

        return response()->json([
            'message' => 'Bien registrado correctamente',
            'data' => $this->transformGood($good)
        ], 201);
    }

    /**
     * Actualizar un bien existente.
     */
    public function update(UpdateGoodsRequest $request, $id)
    {
        $good = Goods::findOrFail($id);
        $validated = $request->validated();

        // Mapear provider_id al nombre de columna si se envía
        if (isset($validated['provider_id'])) {
            $validated['id_provider'] = $validated['provider_id'];
            unset($validated['provider_id']);
        }

        $good->update($validated);
        $good->load(['status', 'category', 'provider']);

        return response()->json([
            'message' => 'Bien actualizado correctamente',
            'data' => $this->transformGood($good)
        ]);
    }

    /**
     * Eliminar un bien.
     */
    public function destroy($id)
    {
        $good = Goods::findOrFail($id);
        $good->delete();

        return response()->json(['message' => 'Bien eliminado correctamente']);
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
            'created_at' => $good->created_at,
            'updated_at' => $good->updated_at,
            'status' => $good->status ? ['id' => $good->status->id, 'name' => $good->status->name] : null,
            'category' => $good->category ? ['id' => $good->category->id, 'name' => $good->category->name] : null,
            'provider' => $good->provider ? ['id' => $good->provider->id, 'name' => $good->provider->name] : null,
        ];
    }
}
