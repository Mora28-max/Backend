<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    /**
     * Filtrar categorías tipo Bienes.
     */
    public function bienes()
    {
        $categories = Category::where('type', 'Bienes')->get();
        return response()->json($categories);
    }

    /**
     * Filtrar categorías tipo Inmuebles.
     */
    public function inmuebles()
    {
        $categories = Category::where('type', 'Inmuebles')->get();
        return response()->json($categories);
    }

    /**
     * Crear nueva categoría.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:Bienes,Inmuebles',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Categoría creada correctamente',
            'data' => $category
        ]);
    }

    /**
     * Mostrar categoría específica.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Actualizar categoría.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:Bienes,Inmuebles',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Categoría actualizada correctamente',
            'data' => $category
        ]);
    }

    /**
     * Eliminar categoría.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Categoría eliminada correctamente']);
    }
}
