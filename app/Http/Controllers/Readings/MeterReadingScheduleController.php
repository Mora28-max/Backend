<?php

namespace App\Http\Controllers\Readings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Readings\MeterReadingSchedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\MeterReading\MeterReadingScheduleService;
use App\Http\Requests\Schedule\StoreMeterReadScheduleRequest;
use App\Http\Resources\Schedule\MeterReadingScheduleResource;
use App\Http\Requests\Schedule\UpdateMeterReadScheduleRequest;
use App\Http\Resources\Schedule\MeterReadingScheduleCollection;

class MeterReadingScheduleController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected MeterReadingScheduleService $meter_reading_schedule_service) {}

    public function index()
    {
        $this->authorize('viewAny', MeterReadingSchedule::class);
        $meterReadingSchedules = MeterReadingSchedule::where('user_id', Auth::user()->id)
            ->where('process_status_id', 1)
            ->paginate(10);
        return new MeterReadingScheduleCollection($meterReadingSchedules);
    }

    public function store(StoreMeterReadScheduleRequest $request)
    {
        try {
            $this->authorize('create', MeterReadingSchedule::class);
            $meterReadingSchedule = $this->meter_reading_schedule_service->storeMeterReadScheduleRequest($request->validated());
            return response([
                'message' => 'El apartado ha sido creado correctamente.',
                'meter_reading_schedule' => new MeterReadingScheduleResource($meterReadingSchedule),
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Hubo un error al agregar a la agenda',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show(MeterReadingSchedule $meter_reading_schedule)
    {
        $this->authorize('view', $meter_reading_schedule);
        return new MeterReadingScheduleResource($meter_reading_schedule);
    }

    public function update(UpdateMeterReadScheduleRequest $request, MeterReadingSchedule $meter_reading_schedule)
    {
        try {

            $this->authorize('update', $meter_reading_schedule);
            $reading = $this->meter_reading_schedule_service->updateReadingSchedule($request->validated(), $meter_reading_schedule);
            return response([
                'message' => 'El apartado de lectura ha sido actualizado correctamente.',
                'meter_reading_schedule' => new MeterReadingScheduleResource($reading),
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $meterReadingSchedule = MeterReadingSchedule::find($id);
        if (!$meterReadingSchedule) {
            return response([
                'message' => 'No se encontró el apartado solicitado.'
            ], 404);
        }

        $this->authorize('delete', $meterReadingSchedule);

        if ($meterReadingSchedule->process_status_id === 5) {
            return response([
                'message' => 'No se puede eliminar el apartado ya procesado.',
            ], 409);
        }

        $meterReadingSchedule->delete();

        return response([
            'message' => 'Apartado eliminado exitosamente.',
        ], 200);
    }

    public function showFinishReading()
    {
        $this->authorize('viewAny', MeterReadingSchedule::class);

        $finishReading = MeterReadingSchedule::where('process_status_id', 5)
            ->paginate(15);

        if ($finishReading->isEmpty())
            return response([
                'message' => 'No se encontraron registros',
            ], 404);

        return new MeterReadingScheduleCollection($finishReading);
    }
}
