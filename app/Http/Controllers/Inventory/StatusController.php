<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Status;

class StatusController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Status::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:statuses',
        ]);

        $status = Status::create($data);
        return response()->json(['message' => 'Estado creado', 'data' => $status], 201);
    }

    public function show($id)
    {
        $status = Status::find($id);
        if (!$status) return response()->json(['message' => 'No encontrado'], 404);
        return response()->json(['data' => $status]);
    }

    public function update(Request $request, $id)
    {
        $status = Status::find($id);
        if (!$status) return response()->json(['message' => 'No encontrado'], 404);

        $data = $request->validate([
            'name' => 'required|string|max:50|unique:statuses,name,'.$id,
        ]);

        $status->update($data);
        return response()->json(['message' => 'Estado actualizado', 'data' => $status]);
    }

    public function destroy($id)
    {
        $status = Status::find($id);
        if (!$status) return response()->json(['message' => 'No encontrado'], 404);

        $status->delete();
        return response()->json(['message' => 'Estado eliminado']);
    }
}
