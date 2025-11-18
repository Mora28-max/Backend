<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance\MaintenanceHistory;
use App\Http\Requests\Maintenance\StoreMaintenanceHistoryRequest;
use App\Http\Requests\Maintenance\UpdateMaintenanceHistoryRequest;
use App\Http\Resources\Maintenance\MaintenanceHistoryCollection;
use App\Http\Resources\Maintenance\MaintenanceHistoryResource;

class MaintenanceHistoryController extends Controller
{
    /**
     * Listar todos los historiales
     */
    public function index(Request $request)
    {
         $search = $request->input('search');

    // Cargar relaciones
    $query = MaintenanceHistory::with(['typeMaintenance', 'goods', 'user']);

    // 🔍 Filtro de búsqueda
    if ($search) {
        $query->where(function ($q) use ($search) {
            // Buscar por código del bien
            $q->whereHas('goods', function ($sub) use ($search) {
                $sub->where('code_goods', 'LIKE', "%{$search}%");
            })
            // O por tipo de mantenimiento
            ->orWhereHas('typeMaintenance', function ($sub) use ($search) {
                $sub->where('name', 'LIKE', "%{$search}%");
            });
        });
    }

    // Ordenar y paginar
    $histories = $query
        ->orderBy('id', 'asc')
        ->paginate(25)
        ->withQueryString();

    return new MaintenanceHistoryCollection($histories);
    }

    /**
     * Mostrar un historial específico
     */
    public function show($id)
    {
        $history = MaintenanceHistory::with(['typeMaintenance', 'goods', ])->find($id);

        if (!$history) {
            return response()->json(['message' => 'Historial no encontrado'], 404);
        }

        return response()->json(['data' => $this->transformHistory($history)]);
    }

    /**
     * Crear historial
     */
    public function store(StoreMaintenanceHistoryRequest $request)
{
    // Validar datos
    $validated = $request->validated();

    // Asignar usuario responsable automáticamente si no se envía
    $validated['id_user'] = $validated['id_user'] ?? auth()->id() ?? 1;

    // Generar código secuencial (1, 2, 3…)
    $lastHistory = MaintenanceHistory::latest('id')->first();
    $validated['code'] = $lastHistory ? $lastHistory->id + 1 : 1;

    // Asignar fecha del próximo mantenimiento automáticamente si no viene
    if (!isset($validated['next_maintenance_date']) || empty($validated['next_maintenance_date'])) {
        // Ejemplo: próximo mantenimiento 3 meses después de la fecha actual
        $validated['next_maintenance_date'] = now()->addMonths(3)->format('Y-m-d');
    }

    // Crear el registro
    $history = MaintenanceHistory::create($validated);

    // Cargar relaciones
    $history->load(['typeMaintenance', 'goods', 'user']);

    // Devolver respuesta
    return response()->json([
        'message' => 'Historial registrado correctamente',
        'data' => $this->transformHistory($history)
    ], 201);
}


    /**
     * Actualizar historial
     */
    public function update(UpdateMaintenanceHistoryRequest $request, $id)
    {
        $history = MaintenanceHistory::findOrFail($id);
    $validated = $request->validated();

    // Mapear description a observations si el frontend envía description
    if (isset($validated['description'])) {
        $validated['observations'] = $validated['description'];
        unset($validated['description']);
    }

    $history->update($validated);
    $history->load(['typeMaintenance', 'goods', 'user']);

    return response()->json([
        'message' => 'Historial actualizado correctamente',
        'data' => $this->transformHistory($history)
    ]);
    }

    /**
     * Eliminar historial
     */
    public function destroy($id)
    {
        $history = MaintenanceHistory::findOrFail($id);
        $history->delete();

        return response()->json(['message' => 'Historial eliminado correctamente']);
    }

    /**
     * Transformar historial para respuesta limpia
     */
 private function transformHistory(MaintenanceHistory $history)
{
    
        return [
            'id' => $history->id,
            'code' => $history->code,
            'date' => $history->date,
            'observations' => $history->observations, // antes 'description', ahora 'observations'
            'cost' => $history->cost,
            'next_maintenance_date' => $history->next_maintenance_date,
            'type_maintenance_id' => $history->id_type_maintenance,
            'goods_id' => $history->id_goods,
            'user_id' => $history->id_user,
            'created_at' => $history->created_at,
            'updated_at' => $history->updated_at,

            // Relación typeMaintenance
            'typeMaintenance' => $history->typeMaintenance ? [
                'id' => $history->typeMaintenance->id,
                'name' => $history->typeMaintenance->name,
            ] : null,

            // Relación goods
            'goods' => $history->goods ? [
                'id' => $history->goods->id,
                'code_goods' => $history->goods->code_goods,
                'name' => $history->goods->name,
            ] : null,

            // Relación user
            'user' => $history->user ? [
                'id' => $history->user->id,
                'firstname' => $history->user->firstname,
            ] : null,
        ];
    }
}