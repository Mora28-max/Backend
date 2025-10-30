<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\GoodsStatus;
use Illuminate\Http\Request;

class GoodsStatusController extends Controller
{
    public function index()
    {
        return response()->json(GoodsStatus::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100'
        ]);

        $status = GoodsStatus::create($validated);

        return response()->json(['message' => 'Estado creado correctamente', 'data' => $status]);
    }

    public function show($id)
    {
        $status = GoodsStatus::findOrFail($id);
        return response()->json($status);
    }

    public function update(Request $request, $id)
    {
        $status = GoodsStatus::findOrFail($id);

        $status->update($request->validate(['name' => 'required|string|max:100']));

        return response()->json(['message' => 'Estado actualizado correctamente', 'data' => $status]);
    }

    public function destroy($id)
    {
        $status = GoodsStatus::findOrFail($id);
        $status->delete();

        return response()->json(['message' => 'Estado eliminado correctamente']);
    }
}
