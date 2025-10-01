<?php

namespace App\Http\Controllers\WaterTank;

use Illuminate\Http\Request;
use FontLib\TrueType\Collection;
use App\Http\Controllers\Controller;
use App\Models\WaterTank\WaterTankLog;
use App\Http\Resources\WaterTank\WaterTankCollection;
use App\Http\Requests\WaterTank\StoreWaterTankRequest;

class TankLogController extends Controller
{

    public function index(Request $request)
    {
        $tank = $request->input('tank') ?? 1;
        $month = $request->input('month') ?? now()->month;
        $year = $request->input('year') ?? now()->year;

        $logs = WaterTankLog::where('water_tank_id', $tank)
            ->whereMonth('log_date', $month)
            ->whereYear('log_date', $year)
            ->get();

        return new WaterTankCollection($logs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWaterTankRequest $request)
    {
        $log = WaterTankLog::create($request->validated());
        return response([
            'message' => 'Registro creado con éxito',
            'data' => $log
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaterTankLog $water_tank_log)
    {
        $water_tank_log->delete();
        return response([
            'message' => 'Registro eliminado con éxito',
        ]);
    }
}
